<?php

namespace App\Livewire;

use App\Models\Table;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Drink;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicMenu extends Component
{
    public Table $table;
    public $categories = [];
    public $dishes = [];
    public $drinks = [];
    public $selectedCategory = 'all';
    public $availableSlots = 0;

    public function mount(string $token)
    {
        $this->table = Table::byQrToken($token)
            ->with(['menu'])
            ->firstOrFail();

        $this->loadMenuData();
        $this->updateBuffetStatus();
    }

    private function formatImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // Si ya es una URL completa (http o https), retornarla tal cual
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        // Si no, prefijarlo con storage/
        return asset('storage/' . $imagePath);
    }

    public function loadMenuData()
    {
        $menu = $this->table->menu;

        if (!$menu) {
            $this->categories = Category::orderBy('name')->get()->toArray();
            $this->dishes = Dish::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get()
                ->map(function ($dish) {
                    return [
                        'id' => $dish->id,
                        'name' => $dish->name,
                        'description' => $dish->description,
                        'price' => (float) $dish->price,
                        'image' => $this->formatImageUrl($dish->image),
                        'category_id' => $dish->category_id,
                        'allergens' => $dish->allergens->pluck('name')->toArray(),
                        'type' => 'dish',
                    ];
                })
                ->toArray();

            $this->drinks = Drink::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get()
                ->map(function ($drink) {
                    return [
                        'id' => $drink->id,
                        'name' => $drink->name,
                        'description' => $drink->description,
                        'price' => (float) $drink->price,
                        'image' => $this->formatImageUrl($drink->image),
                        'category_id' => $drink->category_id,
                        'allergens' => method_exists($drink, 'allergens')
                            ? $drink->allergens->pluck('name')->toArray()
                            : [],
                        'type' => 'drink',
                    ];
                })
                ->toArray();
        } else {
            $this->dishes = $menu->dishes()
                ->with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get()
                ->map(function ($dish) use ($menu) {
                    $extraPrice = $menu->getDishPrice($dish->id);
                    return [
                        'id' => $dish->id,
                        'name' => $dish->name,
                        'description' => $dish->description,
                        // Only show a per-dish price when it's a special (extraPrice !== null)
                        'price' => $extraPrice !== null ? (float) $extraPrice : null,
                        'is_special' => (bool) $dish->pivot->is_special,
                        'image' => $this->formatImageUrl($dish->image),
                        'category_id' => $dish->category_id,
                        'allergens' => $dish->allergens->pluck('name')->toArray(),
                        'type' => 'dish',
                    ];
                })
                ->toArray();

            $this->drinks = Drink::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get()
                ->map(function ($drink) {
                    return [
                        'id' => $drink->id,
                        'name' => $drink->name,
                        'description' => $drink->description,
                        'price' => (float) $drink->price,
                        'image' => $this->formatImageUrl($drink->image),
                        'category_id' => $drink->category_id,
                        'allergens' => method_exists($drink, 'allergens')
                            ? $drink->allergens->pluck('name')->toArray()
                            : [],
                        'type' => 'drink',
                    ];
                })
                ->toArray();

            $categoryIds = collect($this->dishes)->pluck('category_id')
                ->merge(collect($this->drinks)->pluck('category_id'))
                ->filter()
                ->unique();

            $this->categories = Category::whereIn('id', $categoryIds)
                ->orderBy('name')
                ->get()
                ->toArray();
        }
    }

    public function selectCategory()
    {
        $categoryId = request('category_id', 'all');
        $this->selectedCategory = $categoryId;
    }

    public function addProductToCart($product_id = null, $product_type = null)
    {
        if (!$product_id || !$product_type) {
            session()->flash('notification', ['message' => 'Error al obtener el producto', 'type' => 'error']);
            return;
        }
        
        // Dispatch event that will be caught by the public-cart component
        $this->dispatch('add-to-cart', id: (int)$product_id, type: $product_type, quantity: 1);
    }

    private function getFilteredProducts()
    {
        $allProducts = array_merge($this->dishes, $this->drinks);

        if ($this->selectedCategory === 'all') {
            return $allProducts;
        }

        return array_filter($allProducts, function ($product) {
            return $product['category_id'] == $this->selectedCategory;
        });
    }

    public function updateBuffetStatus()
    {
        $limit = 5 * $this->table->capacity;
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Count items from orders in the last 10 minutes for this table
        $recentDishes = DB::table('dish_order')
            ->join('orders', 'dish_order.order_id', '=', 'orders.id')
            ->where('orders.table_id', $this->table->id)
            ->where(function ($query) use ($tenMinutesAgo) {
                $query->where('orders.date', '>=', $tenMinutesAgo)
                      ->orWhere(function ($q) use ($tenMinutesAgo) {
                          $q->whereNull('orders.date')
                            ->where('orders.created_at', '>=', $tenMinutesAgo);
                      });
            })
            ->sum('dish_order.quantity');

        $recentDrinks = DB::table('drink_order')
            ->join('orders', 'drink_order.order_id', '=', 'orders.id')
            ->where('orders.table_id', $this->table->id)
            ->where(function ($query) use ($tenMinutesAgo) {
                $query->where('orders.date', '>=', $tenMinutesAgo)
                      ->orWhere(function ($q) use ($tenMinutesAgo) {
                          $q->whereNull('orders.date')
                            ->where('orders.created_at', '>=', $tenMinutesAgo);
                      });
            })
            ->sum('drink_order.quantity');

        $totalRecentItems = $recentDishes + $recentDrinks;
        $this->availableSlots = max(0, $limit - $totalRecentItems);
    }

    public function render()
    {
        return view('livewire.public-menu', [
            'filteredProducts' => $this->getFilteredProducts(),
        ]);
    }
}


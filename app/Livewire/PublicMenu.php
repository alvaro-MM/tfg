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
                        'image' => $dish->image ? asset('storage/' . $dish->image) : null,
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
                        'image' => $drink->image ? asset('storage/' . $drink->image) : null,
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
                    return [
                        'id' => $dish->id,
                        'name' => $dish->name,
                        'description' => $dish->description,
                        'price' => $menu->getDishPrice($dish->id),
                        'is_special' => (bool) $dish->pivot->is_special,
                        'image' => $dish->image ? asset('storage/' . $dish->image) : null,
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
                        'image' => $drink->image ? asset('storage/' . $drink->image) : null,
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

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function getFilteredProductsProperty()
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
            'products' => $this->filteredProducts,
        ]);
    }
}


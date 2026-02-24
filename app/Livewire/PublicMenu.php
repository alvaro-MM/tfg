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
            // Sin menú asignado: mostrar todos los platos y bebidas disponibles
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
            // Con menú asignado:
            //  - Platos que SÍ están en el menú -> se tratan como ahora (incluidos o con precio especial)
            //  - Platos que NO están en el menú -> se pueden pedir con su precio original

            // Platos del menú (para saber cuáles están incluidos y sus pivots)
            $menuDishes = $menu->dishes()
                ->with(['category', 'allergens'])
                ->where('available', true)
                ->get()
                ->keyBy('id');

            // Todos los platos disponibles del restaurante
            $allAvailableDishes = Dish::with(['category', 'allergens'])
                ->where('available', true)
                ->orderBy('name')
                ->get();

            $this->dishes = $allAvailableDishes
                ->map(function ($dish) use ($menu, $menuDishes) {
                    $isInMenu = $menuDishes->has($dish->id);

                    if ($isInMenu) {
                        // Plato está en el menú: aplicar lógica de menú (incluido / precio especial)
                        $extraPrice = $menu->getDishPrice($dish->id);
                        $pivot = $menuDishes[$dish->id]->pivot;

                        $price = $extraPrice !== null ? (float) $extraPrice : null; // null = incluido en menú
                        $isSpecial = (bool) $pivot->is_special;
                    } else {
                        // Plato NO está en el menú: se cobra a su precio normal
                        $price = (float) $dish->price;
                        $isSpecial = false;
                    }

                    return [
                        'id' => $dish->id,
                        'name' => $dish->name,
                        'description' => $dish->description,
                        'price' => $price,
                        'is_special' => $isSpecial,
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

    public function selectCategory($categoryId)
    {
        // Permitir seleccionar "todas" las categorías o una concreta
        $this->selectedCategory = $categoryId === 'all'
            ? 'all'
            : (int) $categoryId;
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


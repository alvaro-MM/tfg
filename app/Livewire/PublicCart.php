<?php

namespace App\Livewire;

use App\Models\Dish;
use App\Models\Drink;
use App\Models\Table;
use App\Models\Order;
use App\Http\Controllers\PublicOrderController;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicCart extends Component
{
    public string $token;
    public array $items = [];
    public int $count = 0;
    public float $total = 0.00;
    public bool $showCart = false;

    public function mount(string $token)
    {
        $this->token = $token;
        $this->loadCart();
    }

    public function loadCart()
    {
        $cart = session("cart_{$this->token}", ['items' => []]);
        $this->items = $cart['items'] ?? [];
        $this->calculateTotals();
    }

    protected $listeners = ['add-to-cart' => 'addItem'];

    public function addItem(int $id, string $type, int $quantity = 1)
    {
        // Get product
        if ($type === 'dish') {
            $product = Dish::where('id', $id)->where('available', true)->first();
        } else {
            $product = Drink::where('id', $id)->where('available', true)->first();
        }

        if (!$product) {
            session()->flash('notification', ['message' => 'Producto no encontrado o no disponible', 'type' => 'error']);
            return;
        }

        // Get table
        $table = Table::byQrToken($this->token)->first();
        if (!$table) {
            session()->flash('notification', ['message' => 'Mesa no encontrada', 'type' => 'error']);
            return;
        }

        // Calculate current cart count + new quantity
        $currentCount = $this->count;
        $newTotalCount = $currentCount + $quantity;

        // Validate buffet limit BEFORE adding to cart
        $validation = $this->validateBuffetLimit($table, $newTotalCount);
        
        if (!$validation['valid']) {
            $message = "No puedes agregar más productos. Has intentado agregar {$quantity} ítem(s), pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas).";
            session()->flash('notification', ['message' => $message, 'type' => 'error']);
            return;
        }

        // Check if item already exists
        $itemIndex = null;
        foreach ($this->items as $index => $item) {
            if ($item['id'] == $id && $item['type'] == $type) {
                $itemIndex = $index;
                break;
            }
        }

        // Add or update item
        if ($itemIndex !== null) {
            $this->items[$itemIndex]['quantity'] += $quantity;
        } else {
            $this->items[] = [
                'id' => $product->id,
                'type' => $type,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'menu_id' => $table->menu_id,
            ];
        }

        $this->saveCart();
        session()->flash('notification', ['message' => 'Producto agregado al carrito', 'type' => 'success']);
    }

    /**
     * Validate buffet limit: 5 items per person in a sliding 10-minute window
     */
    private function validateBuffetLimit(Table $table, int $newItemsCount): array
    {
        $limit = 5 * $table->capacity;
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Count items from orders in the last 10 minutes for this table
        $recentDishes = DB::table('dish_order')
            ->join('orders', 'dish_order.order_id', '=', 'orders.id')
            ->where('orders.table_id', $table->id)
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
            ->where('orders.table_id', $table->id)
            ->where(function ($query) use ($tenMinutesAgo) {
                $query->where('orders.date', '>=', $tenMinutesAgo)
                      ->orWhere(function ($q) use ($tenMinutesAgo) {
                          $q->whereNull('orders.date')
                            ->where('orders.created_at', '>=', $tenMinutesAgo);
                      });
            })
            ->sum('drink_order.quantity');

        $totalRecentItems = $recentDishes + $recentDrinks;
        $availableSlots = max(0, $limit - $totalRecentItems);

        return [
            'valid' => $newItemsCount <= $availableSlots,
            'total_recent' => $totalRecentItems,
            'limit' => $limit,
            'available' => $availableSlots,
            'requested' => $newItemsCount,
        ];
    }

    public function removeItem(int $id, string $type)
    {
        $this->items = array_filter($this->items, function ($item) use ($id, $type) {
            return !($item['id'] == $id && $item['type'] == $type);
        });

        $this->items = array_values($this->items);
        $this->saveCart();
    }

    public function updateQuantity(int $id, string $type, int $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($id, $type);
            return;
        }

        // Get table
        $table = Table::byQrToken($this->token)->first();
        if (!$table) {
            session()->flash('notification', ['message' => 'Mesa no encontrada', 'type' => 'error']);
            return;
        }

        // Find the item being updated
        $itemIndex = null;
        $oldQuantity = 0;
        foreach ($this->items as $index => $item) {
            if ($item['id'] == $id && $item['type'] == $type) {
                $itemIndex = $index;
                $oldQuantity = $item['quantity'];
                break;
            }
        }

        if ($itemIndex === null) {
            return;
        }

        // Calculate new total count (current count - old quantity + new quantity)
        $currentCount = $this->count;
        $newTotalCount = $currentCount - $oldQuantity + $quantity;

        // Validate buffet limit BEFORE updating quantity
        $validation = $this->validateBuffetLimit($table, $newTotalCount);
        
        if (!$validation['valid']) {
            $message = "No puedes aumentar la cantidad. Solo puedes pedir {$validation['available']} más ítems en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas).";
            session()->flash('notification', ['message' => $message, 'type' => 'error']);
            return;
        }

        // Update quantity
        $this->items[$itemIndex]['quantity'] = $quantity;
        $this->saveCart();
    }

    public function clearCart()
    {
        $this->items = [];
        session()->forget("cart_{$this->token}");
        $this->calculateTotals();
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }

    public function openCart()
    {
        $this->showCart = true;
    }

    public function closeCart()
    {
        $this->showCart = false;
    }

    private function saveCart()
    {
        session(["cart_{$this->token}" => ['items' => $this->items]]);
        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->count = 0;
        $this->total = 0.00;

        // Get table to access menu
        $table = Table::byQrToken($this->token)->with(['menu'])->first();
        $menu = $table?->menu;

        foreach ($this->items as $item) {
            $this->count += $item['quantity'];
            
            $itemPrice = $item['price'];
            
            // If we have a menu and this is a dish (not a drink), recalculate price
            if ($menu && $item['type'] === 'dish') {
                $itemPrice = $menu->getDishPrice($item['id']);
            }
            
            $this->total += ($itemPrice * $item['quantity']);
        }

        $this->total = round($this->total, 2);
    }

    public function sendToKitchen()
    {
        if ($this->count === 0) {
            session()->flash('notification', ['message' => 'El carrito está vacío', 'type' => 'error']);
            return;
        }

        // Get table
        $table = Table::byQrToken($this->token)->first();
        if (!$table) {
            session()->flash('notification', ['message' => 'Mesa no encontrada', 'type' => 'error']);
            return;
        }

        // Validate buffet limit BEFORE sending to kitchen
        $validation = $this->validateBuffetLimit($table, $this->count);
        
        if (!$validation['valid']) {
            $message = "No puedes enviar el pedido. Has intentado pedir {$validation['requested']} ítems, pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas).";
            session()->flash('notification', ['message' => $message, 'type' => 'error']);
            return;
        }

        // Create order directly (same logic as controller)
        try {
            DB::beginTransaction();

            $userId = $table->user_id ?? \App\Models\User::role('admin')->first()?->id ?? 1;

            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'table_id' => $table->id,
                'type' => 'buffet',
                'date' => now(),
            ]);

            // Attach dishes and drinks with quantities
            foreach ($this->items as $item) {
                if ($item['type'] === 'dish') {
                    $order->dishes()->attach($item['id'], [
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $order->drinks()->attach($item['id'], [
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clear cart
            $this->items = [];
            session()->forget("cart_{$this->token}");
            $this->calculateTotals();
            $this->showCart = false;

            DB::commit();

            // Store order ID for redirect
            $orderId = $order->id;
            
            // Use JavaScript redirect to avoid method issues
            $this->dispatch('redirect-to-confirm', url: route('public.order.confirm', ['token' => $this->token, 'orderId' => $orderId]));
            
            session()->flash('notification', ['message' => 'Pedido enviado a cocina exitosamente', 'type' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error sending order to kitchen: ' . $e->getMessage());
            session()->flash('notification', ['message' => 'Error al enviar el pedido: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        return view('livewire.public-cart');
    }
}


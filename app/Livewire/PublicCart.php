<?php

namespace App\Livewire;

use App\Models\Dish;
use App\Models\Drink;
use App\Models\Table;
use App\Models\Order;
use App\Http\Traits\ManagesBuffetLimit;
use App\Http\Controllers\PublicOrderController;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class PublicCart extends Component
{
    use ManagesBuffetLimit;

    public string $token;
    public array $items = [];
    public int $count = 0;
    public float $total = 0.00;
    public bool $showCart = false;
    public bool $showConfirmModal = false;

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
        $this->dispatch('cart-updated');
    }

    #[On('add-to-cart')]
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
            // Para asegurar la reactividad, es mejor reasignar el elemento del array
            $item = $this->items[$itemIndex];
            $item['quantity'] += $quantity;
            $this->items[$itemIndex] = $item;
        } else {
            // Determine item price: for dishes in a menu, use menu extra price (specials) or 0
            if ($type === 'dish' && $table->menu) {
                $price = $table->menu->getDishPrice($product->id) ?? 0.00;
            } else {
                $price = (float) $product->price;
            }

            $this->items[] = [
                'id' => $product->id,
                'type' => $type,
                'name' => $product->name,
                'price' => (float) $price,
                'quantity' => $quantity,
                'menu_id' => $table->menu_id,
            ];
        }

        $this->saveCart();
        session()->flash('notification', ['message' => 'Producto agregado al carrito', 'type' => 'success']);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $id, string $type)
    {
        $this->items = array_filter($this->items, function ($item) use ($id, $type) {
            return !($item['id'] == $id && $item['type'] == $type);
        });

        $this->items = array_values($this->items);
        $this->saveCart();
        $this->dispatch('cart-updated');
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
        $item = $this->items[$itemIndex];
        $item['quantity'] = $quantity;
        $this->items[$itemIndex] = $item;

        $this->saveCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        $this->items = [];
        session()->forget("cart_{$this->token}");
        $this->calculateTotals();
        $this->dispatch('cart-updated');
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

    public function openConfirmModal()
    {
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function confirmSendToKitchen()
    {
        $this->closeConfirmModal();
        $this->sendToKitchen();
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
            // Menu->getDishPrice now returns null for non-special dishes (covered by menu),
            // so coalesce to 0 to avoid adding menu-covered items again.
            if ($menu && $item['type'] === 'dish') {
                $itemPrice = $menu->getDishPrice($item['id']) ?? 0.00;
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

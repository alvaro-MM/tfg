<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class PublicOrderController extends Controller
{
    /**
     * Validate buffet limit: 5 items per person in a sliding 10-minute window
     * Uses orders.date (or orders.created_at if date is null) to determine when orders were placed
     */
    public function validateBuffetLimit(Table $table, int $newItemsCount): array
    {
        $limit = 5 * $table->capacity;
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Count items from orders in the last 10 minutes for this table
        // Use orders.date (or orders.created_at as fallback) to filter recent orders
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

    /**
     * Send order to kitchen (create order without payment)
     */
    public function sendToKitchen(Request $request, string $token): JsonResponse|RedirectResponse
    {
        $table = Table::byQrToken($token)->firstOrFail();
        
        // Get cart from session
        $cart = session("cart_{$token}", ['items' => []]);
        $items = $cart['items'] ?? [];
        
        if (empty($items)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'El carrito está vacío'
                ], 400);
            }
            return redirect()->route('public.menu', $token)
                ->with('error', 'El carrito está vacío');
        }

        // Calculate totals
        $count = 0;
        $total = 0.00;
        foreach ($items as $item) {
            $count += $item['quantity'];
            $total += ($item['price'] * $item['quantity']);
        }
        $total = round($total, 2);

        // Validate buffet limit
        $validation = $this->validateBuffetLimit($table, $count);

        if (!$validation['valid']) {
            $message = "Has pedido {$validation['requested']} ítems, pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas)";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Límite de buffet excedido',
                    'message' => $message,
                    'validation' => $validation,
                ], 422);
            }
            
            return redirect()->route('public.menu', $token)
                ->with('error', $message);
        }

        try {
            DB::beginTransaction();

            // Create order - using table's user_id if available, otherwise get first admin user
            $userId = $table->user_id ?? User::role('admin')->first()?->id ?? 1;

            $order = Order::create([
                'user_id' => $userId,
                'table_id' => $table->id,
                'type' => 'buffet',
                'date' => now(),
            ]);

            // Attach dishes and drinks with quantities
            foreach ($items as $item) {
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
            session()->forget("cart_{$token}");

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Pedido enviado a cocina exitosamente',
                ]);
            }

            return redirect()->route('public.order.confirm', ['token' => $token, 'orderId' => $order->id])
                ->with('success', 'Pedido enviado a cocina exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending order to kitchen: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error al enviar el pedido',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('public.menu', $token)
                ->with('error', 'Error al enviar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Show payment form (for final payment of all orders)
     */
    public function showPayment(string $token): View|RedirectResponse
    {
        $table = Table::byQrToken($token)->firstOrFail();
        
        // Get all orders for this table that don't have an invoice (pending payment)
        $orders = Order::with(['dishes', 'drinks'])
            ->where('table_id', $table->id)
            ->whereNull('invoice_id')
            ->orderBy('date', 'asc')
            ->get();
        
        if ($orders->isEmpty()) {
            return redirect()->route('public.menu', $token)
                ->with('info', 'No hay pedidos pendientes de pago.');
        }

        // Calculate total from all orders
        $total = 0.00;
        $allItems = [];
        
        foreach ($orders as $order) {
            foreach ($order->dishes as $dish) {
                $total += $dish->price * $dish->pivot->quantity;
                $allItems[] = [
                    'order_id' => $order->id,
                    'name' => $dish->name,
                    'price' => $dish->price,
                    'quantity' => $dish->pivot->quantity,
                    'type' => 'dish',
                ];
            }
            foreach ($order->drinks as $drink) {
                $total += $drink->price * $drink->pivot->quantity;
                $allItems[] = [
                    'order_id' => $order->id,
                    'name' => $drink->name,
                    'price' => $drink->price,
                    'quantity' => $drink->pivot->quantity,
                    'type' => 'drink',
                ];
            }
        }
        
        $total = round($total, 2);

        return view('public.payment', [
            'table' => $table,
            'token' => $token,
            'orders' => $orders,
            'cartItems' => $allItems,
            'total' => $total,
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function checkout(Request $request, string $token): JsonResponse|RedirectResponse
    {
        $table = Table::byQrToken($token)->firstOrFail();
        
        // Validate payment form data (only if not JSON request)
        if (!$request->expectsJson()) {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_notes' => 'nullable|string|max:1000',
                'payment_method' => 'required|in:cash,card,mobile,transfer',
                'accept_terms' => 'required|accepted',
            ]);
        } else {
            // For JSON requests, use default values
            $validated = [
                'customer_name' => $request->input('customer_name', 'Cliente'),
                'customer_email' => $request->input('customer_email', ''),
                'customer_phone' => $request->input('customer_phone', ''),
                'customer_notes' => $request->input('customer_notes'),
                'payment_method' => $request->input('payment_method', 'cash'),
            ];
        }
        
        // Get cart from session
        $cart = session("cart_{$token}", ['items' => []]);
        $items = $cart['items'] ?? [];
        
        if (empty($items)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'El carrito está vacío'
                ], 400);
            }
            return redirect()->route('public.menu', $token)
                ->with('error', 'El carrito está vacío');
        }

        // Calculate totals
        $count = 0;
        $total = 0.00;
        foreach ($items as $item) {
            $count += $item['quantity'];
            $total += ($item['price'] * $item['quantity']);
        }
        $total = round($total, 2);

        $validation = $this->validateBuffetLimit($table, $count);

        if (!$validation['valid']) {
            $message = "Has pedido {$validation['requested']} ítems, pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas)";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Límite de buffet excedido',
                    'message' => $message,
                    'validation' => $validation,
                ], 422);
            }
            
            return redirect()->route('public.payment', $token)
                ->with('error', $message);
        }

        try {
            DB::beginTransaction();

            // Create order - using table's user_id if available, otherwise get first admin user
            $userId = $table->user_id ?? User::role('admin')->first()?->id ?? 1;

            $order = Order::create([
                'user_id' => $userId,
                'table_id' => $table->id,
                'type' => 'buffet',
                'date' => now(),
            ]);

            // Attach dishes and drinks with quantities
            foreach ($items as $item) {
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

            // Create invoice with customer and payment information
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'table_id' => $table->id,
                'total' => $total,
                'date' => now(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_notes' => $validated['customer_notes'] ?? null,
                'payment_method' => $validated['payment_method'],
            ]);

            // Update order with invoice_id
            $order->invoice_id = $invoice->id;
            $order->save();

            // Clear cart
            session()->forget("cart_{$token}");

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'message' => 'Pedido creado exitosamente',
                ]);
            }

            return redirect()->route('public.order.confirm', ['token' => $token, 'orderId' => $order->id])
                ->with('success', 'Pedido creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error al procesar el pedido',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('public.menu', $token)
                ->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Show order confirmation
     */
    public function confirm(string $token, int $orderId): View
    {
        $table = Table::byQrToken($token)->firstOrFail();
        $order = Order::with(['dishes', 'drinks', 'invoice'])
            ->where('id', $orderId)
            ->where('table_id', $table->id)
            ->firstOrFail();

        return view('public.confirm', compact('order', 'table'));
    }

    /**
     * Get buffet status for a table
     */
    public function getBuffetStatus(string $token): JsonResponse
    {
        $table = Table::byQrToken($token)->firstOrFail();
        $validation = $this->validateBuffetLimit($table, 0);

        return response()->json([
            'table' => [
                'id' => $table->id,
                'name' => $table->name,
                'capacity' => $table->capacity,
            ],
            'buffet_status' => [
                'limit' => $validation['limit'],
                'used' => $validation['total_recent'],
                'available' => $validation['available'],
                'window_minutes' => 10,
            ],
        ]);
    }
}


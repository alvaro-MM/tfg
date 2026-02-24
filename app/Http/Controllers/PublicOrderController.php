<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\Invoice;
use App\Http\Traits\ManagesBuffetLimit;
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
    use ManagesBuffetLimit;

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
        $table = Table::byQrToken($token)->with(['menu'])->firstOrFail();
        
        // Get all orders for this table that don't have an invoice (pending payment)
        $orders = Order::with(['dishes', 'drinks', 'table.menu'])
            ->where('table_id', $table->id)
            ->whereNull('invoice_id')
            ->orderBy('date', 'asc')
            ->get();
        
        if ($orders->isEmpty()) {
            return redirect()->route('public.menu', $token)
                ->with('info', 'No hay pedidos pendientes de pago.');
        }

        // Cálculo de total delegando en la lógica central de Order::calculateTotal
        // para cada pedido pendiente, evitando duplicar reglas.
        $total = 0.00;
        $allItems = [];

        foreach ($orders as $order) {
            $orderTotal = $order->calculateTotal($table->menu);
            $total += $orderTotal;

            $allItems[] = [
                'order_id' => $order->id,
                'name' => "Pedido #{$order->id}",
                'price' => $orderTotal,
                'quantity' => 1,
                'type' => 'order',
            ];
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
        $table = Table::byQrToken($token)->with(['menu'])->firstOrFail();
        
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
        
        // Get all pending orders for this table
        $orders = Order::with(['dishes', 'drinks', 'table.menu'])
            ->where('table_id', $table->id)
            ->whereNull('invoice_id')
            ->get();

        if ($orders->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'No hay pedidos pendientes'
                ], 400);
            }
            return redirect()->route('public.menu', $token)
                ->with('error', 'No hay pedidos pendientes de pago');
        }

        // Calcular total delegando en Order::calculateTotal por pedido
        $total = 0.00;

        foreach ($orders as $order) {
            $total += $order->calculateTotal($table->menu);
        }

        // Beverages: la lógica específica de bebidas (primera gratis, resto de pago)
        // ya está incluida dentro de Order::calculateTotal, pero se deja aquí
        // en caso de futuras extensiones; por ahora no sumamos nada extra.
        foreach ($orders as $order) {
            $drinkCount = 0;
            foreach ($order->drinks as $drink) {
                $drinkCount += $drink->pivot->quantity;
                // Only charge from 2nd beverage onwards
                $chargeableQuantity = max(0, $drinkCount - 1);
                // El total de bebidas ya se computa en calculateTotal, no repetir aquí
            }
        }
        $total = round($total, 2);

        try {
            DB::beginTransaction();

            // Create invoice for all orders
            $invoice = Invoice::create([
                'order_id' => $orders->first()->id,
                'table_id' => $table->id,
                'total' => $total,
                'date' => now(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_notes' => $validated['customer_notes'] ?? null,
                'payment_method' => $validated['payment_method'],
            ]);

            // Update all orders with invoice_id
            foreach ($orders as $order) {
                $order->invoice_id = $invoice->id;
                $order->save();
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'invoice_id' => $invoice->id,
                    'total' => $total,
                    'message' => 'Pedido procesado exitosamente',
                ]);
            }

            return redirect()->route('public.order.confirm', ['token' => $token, 'orderId' => $orders->first()->id])
                ->with('success', 'Pedido procesado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing checkout: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error al procesar el pago',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('public.payment', $token)
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
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

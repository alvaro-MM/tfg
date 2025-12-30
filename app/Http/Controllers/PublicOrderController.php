<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use App\Http\Controllers\PublicCartController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class PublicOrderController extends Controller
{
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
            ->where('dish_order.created_at', '>=', $tenMinutesAgo)
            ->sum('dish_order.quantity');

        $recentDrinks = DB::table('drink_order')
            ->join('orders', 'drink_order.order_id', '=', 'orders.id')
            ->where('orders.table_id', $table->id)
            ->where('drink_order.created_at', '>=', $tenMinutesAgo)
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
     * Process checkout and create order
     */
    public function checkout(Request $request, string $token): JsonResponse|View
    {
        $table = Table::byQrToken($token)->firstOrFail();
        
        $cartController = new PublicCartController();
        $cartResponse = $cartController->getCart($request, $token);
        $cartData = json_decode($cartResponse->getContent(), true);
        
        if (empty($cartData['items'])) {
            return response()->json([
                'error' => 'El carrito está vacío'
            ], 400);
        }

        $totalItems = $cartData['count'];
        $validation = $this->validateBuffetLimit($table, $totalItems);

        if (!$validation['valid']) {
            return response()->json([
                'error' => 'Límite de buffet excedido',
                'message' => "Has pedido {$validation['requested']} ítems, pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$table->capacity} personas)",
                'validation' => $validation,
            ], 422);
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
            foreach ($cartData['items'] as $item) {
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

            // Create invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'table_id' => $table->id,
                'total' => $cartData['total'],
                'date' => now(),
            ]);

            // Update order with invoice_id
            $order->invoice_id = $invoice->id;
            $order->save();

            // Clear cart
            $cartController->clearCart($request, $token);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'message' => 'Pedido creado exitosamente',
                ]);
            }

            return redirect()->route('public.order.confirm', ['token' => $token, 'orderId' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al procesar el pedido',
                'message' => $e->getMessage(),
            ], 500);
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


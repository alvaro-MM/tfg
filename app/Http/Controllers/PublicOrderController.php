<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\Invoice;
use App\Http\Traits\ManagesBuffetLimit;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use App\Services\RedsysService;
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

    public function __construct(private readonly RedsysService $redsys)
    {
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
            $people = $table->people_count > 0 ? $table->people_count : $table->capacity;
            $message = "Has pedido {$validation['requested']} ítems, pero solo puedes pedir {$validation['available']} más en los próximos 10 minutos (límite: {$validation['limit']} por {$people} personas)";
            
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
     * Process checkout and create order (puede devolver vista de Redsys o redirect/JSON)
     */
    public function checkout(Request $request, string $token): View|JsonResponse|RedirectResponse
    {
        $table = Table::byQrToken($token)->with(['menu'])->firstOrFail();
        
        // Validate payment form data (only if not JSON request)
        if (!$request->expectsJson()) {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_notes' => 'nullable|string|max:1000',
                'payment_method' => 'required|in:cash,card',
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

        // Tarjeta: iniciar pago con Redsys (solo para flujos web, no JSON)
        if (!$request->expectsJson() && ($validated['payment_method'] ?? null) === 'card') {
            return $this->startRedsysPayment($token, $table, $orders, $validated, $total);
        }

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
                'payment_status' => 'paid',
                'payment_amount' => $total,
                'payment_currency' => 'EUR',
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

            // Cerrar/liberar mesa tras el pago (efectivo u otros métodos no tarjeta)
            $table->update([
                'status' => 'available',
                'user_id' => null,
                'notes' => null,
                'people_count' => $table->capacity,
            ]);

            return redirect()->route('public.thankyou', ['token' => $token])
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
     * Inicia el pago con Redsys para una mesa (token) y sus pedidos pendientes.
     *
     * @param array<string, mixed> $validated
     * @param \Illuminate\Support\Collection<int, \App\Models\Order> $orders
     */
    private function startRedsysPayment(string $token, Table $table, $orders, array $validated, float $total): View|RedirectResponse
    {
        $amountCents = (int) round($total * 100);
        if ($amountCents <= 0) {
            return redirect()->route('public.payment', $token)
                ->with('error', 'El importe no puede ser 0.');
        }

        if (!config('redsys.merchant_code') || !config('redsys.terminal') || !config('redsys.key') || !config('redsys.url')) {
            return redirect()->route('public.payment', $token)
                ->with('error', 'Redsys no está configurado. Contacta con el restaurante.');
        }

        // Número de pedido Redsys: 12 caracteres numéricos
        $orderNumber = str_pad((string) random_int(1, 999999999999), 12, '0', STR_PAD_LEFT);

        // Guardar contexto en sesión para finalizar en callback OK
        session([
            'redsys_checkout' => [
                'token' => $token,
                'table_id' => $table->id,
                'order_ids' => $orders->pluck('id')->values()->toArray(),
                'amount_cents' => $amountCents,
                'order_number' => $orderNumber,
                'customer' => [
                    'name' => $validated['customer_name'] ?? null,
                    'email' => $validated['customer_email'] ?? null,
                    'phone' => $validated['customer_phone'] ?? null,
                    'notes' => $validated['customer_notes'] ?? null,
                ],
            ],
        ]);

        $merchantParams = [
            'DS_MERCHANT_AMOUNT' => (string) $amountCents,
            'DS_MERCHANT_ORDER' => $orderNumber,
            'DS_MERCHANT_MERCHANTCODE' => (string) config('redsys.merchant_code'),
            'DS_MERCHANT_CURRENCY' => (string) config('redsys.currency', '978'),
            'DS_MERCHANT_TRANSACTIONTYPE' => (string) config('redsys.transaction_type', '0'),
            'DS_MERCHANT_TERMINAL' => (string) config('redsys.terminal', '1'),
            'DS_MERCHANT_MERCHANTURL' => route('public.redsys.notify'),
            'DS_MERCHANT_URLOK' => route('public.redsys.ok'),
            'DS_MERCHANT_URLKO' => route('public.redsys.ko'),
            'DS_MERCHANT_MERCHANTNAME' => (string) config('redsys.merchant_name', 'TFG'),
            'DS_MERCHANT_PRODUCTDESCRIPTION' => "Pago mesa {$table->name}",
            // Datos opacos de vuelta (token + mesa) por si hiciera falta
            'DS_MERCHANT_MERCHANTDATA' => base64_encode(json_encode([
                'token' => $token,
                'table_id' => $table->id,
            ])),
        ];

        $fields = $this->redsys->buildFormFields($merchantParams);

        return view('public.redsys.redirect', [
            'actionUrl' => (string) config('redsys.url'),
            'signatureVersion' => $fields['signatureVersion'],
            'merchantParameters' => $fields['merchantParameters'],
            'signature' => $fields['signature'],
        ]);
    }

    /**
     * Callback OK de Redsys (el usuario vuelve tras pagar).
     */
    public function redsysOk(Request $request): RedirectResponse
    {
        $merchantParameters = (string) $request->input('Ds_MerchantParameters', '');
        $signature = (string) $request->input('Ds_Signature', '');

        $ctx = session('redsys_checkout');
        if (!is_array($ctx) || $merchantParameters === '' || $signature === '') {
            return redirect()->route('home')->with('error', 'Sesión de pago inválida.');
        }

        if (!$this->redsys->verifyResponseSignature($merchantParameters, $signature)) {
            return redirect()->route('public.payment', $ctx['token'])
                ->with('error', 'Firma de Redsys inválida.');
        }

        $params = $this->redsys->decodeMerchantParameters($merchantParameters);
        $dsOrder = (string) ($params['Ds_Order'] ?? '');
        $dsAmount = (int) ($params['Ds_Amount'] ?? 0);
        $dsResponse = (int) ($params['Ds_Response'] ?? 999);

        if ($dsOrder !== ($ctx['order_number'] ?? null) || $dsAmount !== (int) ($ctx['amount_cents'] ?? -1) || $dsResponse >= 100) {
            return redirect()->route('public.payment', $ctx['token'])
                ->with('error', 'El pago no ha sido autorizado.');
        }

        try {
            DB::beginTransaction();

            $table = Table::with('menu')->findOrFail($ctx['table_id']);

            $orders = Order::with(['dishes', 'drinks', 'table.menu'])
                ->whereIn('id', $ctx['order_ids'] ?? [])
                ->where('table_id', $table->id)
                ->whereNull('invoice_id')
                ->get();

            if ($orders->isEmpty()) {
                DB::rollBack();
                return redirect()->route('public.payment', $ctx['token'])
                    ->with('error', 'No hay pedidos pendientes de pago.');
            }

            // Recalcular total para evitar manipulación
            $total = round($orders->sum(fn ($o) => $o->calculateTotal($table->menu)), 2);

            $invoice = Invoice::create([
                'order_id' => $orders->first()->id,
                'table_id' => $table->id,
                'total' => $total,
                'date' => now(),
                'customer_name' => $ctx['customer']['name'] ?? 'Cliente',
                'customer_email' => $ctx['customer']['email'] ?? '',
                'customer_phone' => $ctx['customer']['phone'] ?? '',
                'customer_notes' => $ctx['customer']['notes'] ?? null,
                'payment_method' => 'card',
                'payment_status' => 'paid',
                'payment_reference' => $dsOrder,
                'payment_amount' => $total,
                'payment_currency' => 'EUR',
            ]);

            foreach ($orders as $order) {
                $order->invoice_id = $invoice->id;
                $order->save();
            }

            DB::commit();
            session()->forget('redsys_checkout');

            // Cerrar/liberar mesa tras el pago con tarjeta
            $table->update([
                'status' => 'available',
                'user_id' => null,
                'notes' => null,
                'people_count' => $table->capacity,
            ]);

            return redirect()->route('public.thankyou', [
                'token' => $ctx['token'],
            ])->with('success', 'Pago con tarjeta realizado correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Redsys OK error: ' . $e->getMessage());

            return redirect()->route('public.payment', $ctx['token'])
                ->with('error', 'Error al registrar el pago.');
        }
    }

    /**
     * Callback KO de Redsys (cancelación o error de pago).
     */
    public function redsysKo(Request $request): RedirectResponse
    {
        $ctx = session('redsys_checkout');
        $token = is_array($ctx) ? ($ctx['token'] ?? null) : null;

        session()->forget('redsys_checkout');

        if ($token) {
            return redirect()->route('public.payment', $token)
                ->with('error', 'Has cancelado o fallado el pago con tarjeta.');
        }

        return redirect()->route('home')
            ->with('error', 'Has cancelado o fallado el pago con tarjeta.');
    }

    /**
     * Notificación servidor-servidor (opcional) de Redsys.
     */
    public function redsysNotify(Request $request)
    {
        Log::info('Redsys notify', $request->all());
        return response('OK');
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

        // Si el pedido ya tiene factura, consideramos que la mesa ha pagado
        // y redirigimos a la pantalla de agradecimiento dejando la mesa limpia.
        if ($order->invoice) {
            $table->update([
                'status' => 'available',
                'user_id' => null,
                'notes' => null,
                'people_count' => $table->capacity,
            ]);

            return redirect()->route('public.thankyou', ['token' => $token]);
        }

        return view('public.confirm', compact('order', 'table'));
    }

    /**
     * Vista de agradecimiento tras completar el pago
     */
    public function thankYou(string $token): View
    {
        $table = Table::byQrToken($token)->firstOrFail();

        return view('public.thankyou', compact('table'));
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

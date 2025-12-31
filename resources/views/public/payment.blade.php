@extends('layouts.public')

@section('title', 'Proceso de Pago - ' . $table->name)

@section('header-title', 'Proceso de Pago')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Error Messages -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-red-800">Por favor, corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Orders Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen de Pedidos Pendientes</h2>
        @if(isset($orders) && $orders->count() > 0)
            <p class="text-sm text-gray-600 mb-4">Tienes {{ $orders->count() }} pedido(s) pendiente(s) de pago:</p>
        @endif
        
        <div class="space-y-3 mb-4">
            @if(isset($orders) && $orders->count() > 0)
                @foreach($orders as $order)
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Pedido #{{ $order->id }} - {{ $order->date->format('H:i') }}</p>
                        @foreach($order->dishes as $dish)
                            <div class="flex justify-between items-center py-1 border-b border-gray-200">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $dish->name }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $dish->pivot->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">€{{ number_format($dish->price * $dish->pivot->quantity, 2) }}</p>
                                    <p class="text-xs text-gray-500">€{{ number_format($dish->price, 2) }} c/u</p>
                                </div>
                            </div>
                        @endforeach
                        @foreach($order->drinks as $drink)
                            <div class="flex justify-between items-center py-1 border-b border-gray-200">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $drink->name }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $drink->pivot->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">€{{ number_format($drink->price * $drink->pivot->quantity, 2) }}</p>
                                    <p class="text-xs text-gray-500">€{{ number_format($drink->price, 2) }} c/u</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                @foreach($cartItems as $item)
                <div class="flex justify-between items-center py-2 border-b">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-600">Cantidad: {{ $item['quantity'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">€{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        <p class="text-xs text-gray-500">€{{ number_format($item['price'], 2) }} c/u</p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        
        <div class="border-t pt-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-indigo-600">€{{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <form action="{{ route('public.checkout', $token) }}" method="POST" id="paymentForm">
        @csrf
        
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Información del Cliente</h2>
            <p class="text-sm text-gray-600 mb-4">Por favor, proporciona la siguiente información para procesar tu pedido:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Ej: Juan Pérez">
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="customer_email" 
                           name="customer_email" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="ejemplo@email.com">
                    @error('customer_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="customer_phone" 
                           name="customer_phone" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="+34 600 000 000">
                    @error('customer_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="customer_table_number" class="block text-sm font-medium text-gray-700 mb-1">
                        Número de Mesa
                    </label>
                    <input type="text" 
                           id="customer_table_number" 
                           name="customer_table_number" 
                           value="{{ $table->name }}"
                           readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            
            <div class="mt-4">
                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notas adicionales (opcional)
                </label>
                <textarea id="customer_notes" 
                          name="customer_notes" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Alergias, preferencias, instrucciones especiales..."></textarea>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Método de Pago</h2>
            <p class="text-sm text-gray-600 mb-4">Selecciona tu método de pago preferido:</p>
            
            <div class="space-y-3">
                <!-- Cash Payment -->
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors payment-method">
                    <input type="radio" 
                           name="payment_method" 
                           value="cash" 
                           required
                           class="mr-3 w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                           checked>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2m-4-4V5a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4m6 0h6"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">Efectivo</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Pago en efectivo al finalizar</p>
                    </div>
                </label>

                <!-- Card Payment -->
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors payment-method">
                    <input type="radio" 
                           name="payment_method" 
                           value="card" 
                           required
                           class="mr-3 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">Tarjeta</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Pago con tarjeta de crédito o débito</p>
                    </div>
                </label>

                <!-- Mobile Payment -->
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors payment-method">
                    <input type="radio" 
                           name="payment_method" 
                           value="mobile" 
                           required
                           class="mr-3 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">Pago Móvil</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Bizum, Apple Pay, Google Pay u otros métodos móviles</p>
                    </div>
                </label>

                <!-- Bank Transfer -->
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors payment-method">
                    <input type="radio" 
                           name="payment_method" 
                           value="transfer" 
                           required
                           class="mr-3 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">Transferencia Bancaria</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Transferencia bancaria (se enviarán los datos por email)</p>
                    </div>
                </label>
            </div>
            
            @error('payment_method')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" 
                       name="accept_terms" 
                       required
                       class="mt-1 mr-3 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">
                    Acepto los <a href="#" class="text-indigo-600 hover:underline">términos y condiciones</a> y la 
                    <a href="#" class="text-indigo-600 hover:underline">política de privacidad</a>. 
                    <span class="text-red-500">*</span>
                </span>
            </label>
            @error('accept_terms')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('public.menu', $token) }}" 
               class="flex-1 bg-gray-200 text-gray-800 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                Volver al Menú
            </a>
            <button type="submit" 
                    class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                @if(isset($orders) && $orders->count() > 0)
                    Pagar {{ $orders->count() }} Pedido(s) - €{{ number_format($total, 2) }}
                @else
                    Confirmar y Procesar Pago
                @endif
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Highlight selected payment method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(label => {
                label.classList.remove('border-indigo-500', 'bg-indigo-50');
                label.classList.add('border-gray-200');
            });
            
            if (this.checked) {
                this.closest('.payment-method').classList.remove('border-gray-200');
                this.closest('.payment-method').classList.add('border-indigo-500', 'bg-indigo-50');
            }
        });
    });

    // Initialize first payment method as selected
    document.querySelector('input[name="payment_method"]:checked')?.closest('.payment-method')?.classList.add('border-indigo-500', 'bg-indigo-50');
</script>
@endpush
@endsection


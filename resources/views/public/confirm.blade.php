@extends('layouts.public')

@section('title', 'Confirmación de Pedido - ' . $table->name)

@section('header-title', 'Pedido Confirmado')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-2xl shadow-lg">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center animate-bounce">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-green-900">¡Pedido confirmado exitosamente!</h2>
                <p class="mt-2 text-base text-green-700">
                    Tu pedido ha sido procesado y está siendo preparado en cocina.
                </p>
            </div>
        </div>
    </div>

    <!-- Order Details Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Detalles del Pedido</h3>
            <div class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full font-semibold">
                #{{ $order->id }}
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-1">Mesa</p>
                <p class="text-xl font-bold text-gray-900">{{ $table->name }}</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-1">Fecha y Hora</p>
                <p class="text-xl font-bold text-gray-900">{{ $order->date->format('d/m/Y H:i') }}</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl">
                <p class="text-sm font-medium text-gray-600 mb-1">Tipo de Pedido</p>
                <p class="text-xl font-bold text-gray-900">{{ ucfirst($order->type) }}</p>
            </div>
            @if($order->invoice)
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-xl">
                <p class="text-sm font-medium text-indigo-600 mb-1">Factura</p>
                <p class="text-xl font-bold text-indigo-900">#{{ $order->invoice->id }}</p>
            </div>
            @endif
        </div>

        <!-- Items List -->
        <div class="border-t-2 border-gray-200 pt-6">
            <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Productos Pedidos
            </h4>
            
            @if($order->dishes->count() > 0)
            <div class="mb-6">
                <h5 class="font-semibold text-gray-700 mb-3 flex items-center text-lg">
                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                    Platos ({{ $order->dishes->sum('pivot.quantity') }})
                </h5>
                <div class="space-y-3">
                    @foreach($order->dishes as $dish)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-orange-300 transition-colors">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ $dish->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">Cantidad: {{ $dish->pivot->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-gray-900">€{{ number_format($dish->price * $dish->pivot->quantity, 2) }}</p>
                                <p class="text-xs text-gray-500">€{{ number_format($dish->price, 2) }} c/u</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($order->drinks->count() > 0)
            <div class="mb-6">
                <h5 class="font-semibold text-gray-700 mb-3 flex items-center text-lg">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Bebidas ({{ $order->drinks->sum('pivot.quantity') }})
                </h5>
                <div class="space-y-3">
                    @foreach($order->drinks as $drink)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ $drink->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">Cantidad: {{ $drink->pivot->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-gray-900">€{{ number_format($drink->price * $drink->pivot->quantity, 2) }}</p>
                                <p class="text-xs text-gray-500">€{{ number_format($drink->price, 2) }} c/u</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Invoice Total -->
        @if($order->invoice)
        <div class="border-t-2 border-gray-200 pt-6 mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-gray-900">Total</span>
                <span class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">€{{ number_format($order->invoice->total, 2) }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <a href="{{ route('public.menu', $table->qr_token) }}" 
           class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center px-6 py-4 rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span>Volver al Menú</span>
        </a>
        <button onclick="window.print()" 
                class="flex-1 bg-white border-2 border-gray-300 text-gray-800 text-center px-6 py-4 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Imprimir Pedido</span>
        </button>
    </div>

    <!-- Info Message -->
    <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-md">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-900 mb-1">Información Importante</p>
                <p class="text-sm text-blue-800">
                    Puedes seguir haciendo pedidos. Recuerda que el límite es de <strong>5 ítems por persona cada 10 minutos</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection


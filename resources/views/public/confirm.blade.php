@extends('layouts.public')

@section('title', 'Confirmación de Pedido - ' . $table->name)

@section('header-title', 'Pedido Confirmado')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-lg font-semibold text-green-900">¡Pedido confirmado exitosamente!</h2>
        </div>
        <p class="mt-2 text-sm text-green-700">
            Tu pedido ha sido procesado y está siendo preparado.
        </p>
    </div>

    <!-- Order Details Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Detalles del Pedido</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-600">Número de Pedido</p>
                <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Mesa</p>
                <p class="text-lg font-semibold text-gray-900">{{ $table->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Fecha</p>
                <p class="text-lg font-semibold text-gray-900">{{ $order->date->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tipo</p>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($order->type) }}</p>
            </div>
        </div>

        <!-- Items List -->
        <div class="border-t pt-4">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Productos Pedidos</h4>
            
            @if($order->dishes->count() > 0)
            <div class="mb-4">
                <h5 class="font-medium text-gray-700 mb-2">Platos</h5>
                <div class="space-y-2">
                    @foreach($order->dishes as $dish)
                    <div class="flex justify-between items-center py-2 border-b">
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
                </div>
            </div>
            @endif

            @if($order->drinks->count() > 0)
            <div class="mb-4">
                <h5 class="font-medium text-gray-700 mb-2">Bebidas</h5>
                <div class="space-y-2">
                    @foreach($order->drinks as $drink)
                    <div class="flex justify-between items-center py-2 border-b">
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
            </div>
            @endif
        </div>

        <!-- Invoice Total -->
        @if($order->invoice)
        <div class="border-t pt-4 mt-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-indigo-600">€{{ number_format($order->invoice->total, 2) }}</span>
            </div>
            <p class="text-sm text-gray-600 mt-2">Factura #{{ $order->invoice->id }}</p>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('public.menu', $table->qr_token) }}" 
           class="flex-1 bg-indigo-600 text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
            Volver al Menú
        </a>
        <button onclick="window.print()" 
                class="flex-1 bg-gray-200 text-gray-800 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
            Imprimir Pedido
        </button>
    </div>

    <!-- Info Message -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-800">
            <strong>Nota:</strong> Puedes seguir haciendo pedidos. Recuerda que el límite es de 5 ítems por persona cada 10 minutos.
        </p>
    </div>
</div>
@endsection


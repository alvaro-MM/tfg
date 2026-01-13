<div>
    @script
    <script>
        $wire.on('redirect-to-confirm', (event) => {
            window.location.href = event.url;
        });
    </script>
    @endscript
    
    <!-- Notifications -->
    @if(session('notification'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 {{ session('notification.type') === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            <span>{{ session('notification.message') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">×</button>
        </div>
    @endif

    <!-- Floating Cart Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button wire:click="openCart" 
                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full p-4 shadow-2xl hover:shadow-indigo-500/50 hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 flex items-center space-x-3 transform hover:scale-110 relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            @if($count > 0)
                <span class="font-bold text-lg">{{ $count }}</span>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                    <span class="text-xs font-bold text-white">{{ $count }}</span>
                </span>
            @else
                <span class="font-semibold">Carrito</span>
            @endif
        </button>
    </div>

    <!-- Cart Modal -->
    @if($showCart)
    <div wire:transition
         class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center transition-opacity duration-300" 
         wire:click="closeCart">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-2xl flex flex-col transform transition-all duration-300" 
             wire:click.stop>
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">Carrito</h2>
                    <p class="text-sm text-indigo-100 mt-1">{{ $count }} {{ $count === 1 ? 'ítem' : 'ítems' }}</p>
                </div>
                <button wire:click="closeCart" class="text-white hover:text-gray-200 transition-colors p-2 hover:bg-white/20 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Items -->
            <div class="flex-1 overflow-y-auto p-6">
                @forelse($items as $index => $item)
                    <div class="bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200 hover:border-indigo-300 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">€{{ number_format($item['price'], 2) }} cada uno</p>
                            </div>
                            <button wire:click="removeItem({{ $item['id'] }}, '{{ $item['type'] }}')" 
                                    class="text-red-500 hover:text-red-700 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 bg-white rounded-lg px-3 py-2 border border-gray-200">
                                <button wire:click="updateQuantity({{ $item['id'] }}, '{{ $item['type'] }}', {{ $item['quantity'] - 1 }})" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-600 hover:text-gray-900 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="font-bold text-gray-900 w-8 text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $item['id'] }}, '{{ $item['type'] }}', {{ $item['quantity'] + 1 }})" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-600 hover:text-gray-900 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-indigo-600">€{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">El carrito está vacío</p>
                        <p class="text-sm text-gray-400 mt-1">Agrega productos desde el menú</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Footer -->
            @if($count > 0)
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-bold text-gray-900">Total:</span>
                    <span class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">€{{ number_format($total, 2) }}</span>
                </div>
                <button wire:click="sendToKitchen" 
                        wire:confirm="¿Enviar pedido de {{ $count }} {{ $count === 1 ? 'ítem' : 'ítems' }} a cocina?"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2"
                        @if($count === 0) disabled @endif>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Enviar a Cocina</span>
                </button>
                <p class="mt-3 text-xs text-center text-gray-500">
                    El pedido se enviará a cocina. Puedes seguir haciendo pedidos. El pago se realizará al final.
                </p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>


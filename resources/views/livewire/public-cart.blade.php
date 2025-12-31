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
        <button wire:click="openCart" class="bg-indigo-600 text-white rounded-full p-4 shadow-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="font-semibold">{{ $count }}</span>
        </button>
    </div>

    <!-- Cart Modal -->
    @if($showCart)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center" wire:click="closeCart">
        <div class="bg-white rounded-t-lg sm:rounded-lg w-full sm:max-w-md max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Carrito</h2>
                    <button wire:click="closeCart" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4 mb-4">
                    @forelse($items as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div class="flex-1">
                                <p class="font-medium">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-600">€{{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="updateQuantity({{ $item['id'] }}, '{{ $item['type'] }}', {{ $item['quantity'] - 1 }})" 
                                        class="text-gray-600 hover:text-gray-900">-</button>
                                <span>{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $item['id'] }}, '{{ $item['type'] }}', {{ $item['quantity'] + 1 }})" 
                                        class="text-gray-600 hover:text-gray-900">+</button>
                                <button wire:click="removeItem({{ $item['id'] }}, '{{ $item['type'] }}')" 
                                        class="ml-4 text-red-600 hover:text-red-900">Eliminar</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">El carrito está vacío</p>
                    @endforelse
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-xl font-bold">€{{ number_format($total, 2) }}</span>
                    </div>
                    <button wire:click="sendToKitchen" 
                            wire:confirm="¿Enviar pedido de {{ $count }} ítem(s) a cocina?"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            @if($count === 0) disabled @endif>
                        Enviar a Cocina
                    </button>
                    <p class="mt-2 text-xs text-center text-gray-500">
                        El pedido se enviará a cocina. Puedes seguir haciendo pedidos. El pago se realizará al final.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


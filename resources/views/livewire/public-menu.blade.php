<div>
    <!-- Notifications -->
    @if(session('notification'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 {{ session('notification.type') === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            <span>{{ session('notification.message') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">×</button>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Buffet Status Indicator -->
        <div class="mb-4 p-4 {{ $availableSlots > 0 ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium {{ $availableSlots > 0 ? 'text-blue-900' : 'text-red-900' }}">Estado del Buffet</p>
                    <p class="text-xs {{ $availableSlots > 0 ? 'text-blue-700' : 'text-red-700' }}">
                        Máximo {{ 5 * $table->capacity }} ítems (5 por persona) cada 10 minutos
                    </p>
                    @if($availableSlots === 0)
                        <p class="text-xs font-semibold text-red-800 mt-1">
                            Has alcanzado el límite. Espera 10 minutos desde tu último pedido.
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold {{ $availableSlots > 0 ? 'text-blue-900' : 'text-red-900' }}">{{ $availableSlots }}</p>
                    <p class="text-xs {{ $availableSlots > 0 ? 'text-blue-700' : 'text-red-700' }}">disponibles</p>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="mb-6 overflow-x-auto">
            <div class="flex space-x-2 border-b border-gray-200">
                <button wire:click="selectCategory('all')" 
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors {{ $selectedCategory === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                    Todos
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category['id'] }})" 
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors {{ $selectedCategory == $category['id'] ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        @if(count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            @if($product['image'])
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 flex items-center justify-center text-gray-400">Sin imagen</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $product['name'] }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $product['description'] ?? '' }}</p>
                            @if(!empty($product['allergens']))
                                <p class="text-xs text-orange-600 mb-2">Alérgenos: {{ implode(', ', $product['allergens']) }}</p>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-indigo-600">€{{ number_format($product['price'], 2) }}</span>
                                <button wire:click="$dispatch('add-to-cart', {id: {{ $product['id'] }}, type: '{{ $product['type'] }}', quantity: 1})" 
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">No hay productos disponibles en esta categoría.</p>
            </div>
        @endif
    </div>

    <!-- Cart Component -->
    @livewire('public-cart', ['token' => $table->qr_token], key('cart-' . $table->qr_token))
    
    <!-- Payment Button (if there are pending orders) -->
    @php
        $pendingOrders = \App\Models\Order::where('table_id', $table->id)
            ->whereNull('invoice_id')
            ->count();
    @endphp
    @if($pendingOrders > 0)
        <div class="fixed bottom-6 left-6 z-50">
            <a href="{{ route('public.payment', $table->qr_token) }}" 
               class="bg-green-600 text-white rounded-full px-6 py-3 shadow-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v4m6 0h6"></path>
                </svg>
                <span class="font-semibold">Pagar ({{ $pendingOrders }})</span>
            </a>
        </div>
    @endif
</div>


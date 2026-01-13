<div>
    <!-- Notifications -->
    @if(session('notification'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 min-w-[300px] max-w-md {{ session('notification.type') === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white' : 'bg-gradient-to-r from-green-500 to-green-600 text-white' }}">
            @if(session('notification.type') === 'error')
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @else
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @endif
            <span class="flex-1 text-sm font-medium">{{ session('notification.message') }}</span>
            <button @click="show = false" class="text-white hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Buffet Status Indicator -->
        <div class="mb-6 p-5 {{ $availableSlots > 0 ? 'bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-300' : 'bg-gradient-to-r from-red-50 to-orange-50 border-red-300' }} border-2 rounded-xl shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        @if($availableSlots > 0)
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-base font-bold {{ $availableSlots > 0 ? 'text-blue-900' : 'text-red-900' }}">Estado del Buffet</p>
                        <p class="text-sm {{ $availableSlots > 0 ? 'text-blue-700' : 'text-red-700' }} mt-1">
                            Máximo {{ 5 * $table->capacity }} ítems (5 por persona) cada 10 minutos
                        </p>
                        @if($availableSlots === 0)
                            <p class="text-sm font-semibold text-red-800 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Has alcanzado el límite. Espera 10 minutos desde tu último pedido.
                            </p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-extrabold {{ $availableSlots > 0 ? 'text-blue-600' : 'text-red-600' }}">{{ $availableSlots }}</p>
                    <p class="text-sm font-medium {{ $availableSlots > 0 ? 'text-blue-700' : 'text-red-700' }}">disponibles</p>
                    <div class="mt-2 w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full {{ $availableSlots > 0 ? 'bg-blue-500' : 'bg-red-500' }} transition-all duration-500" 
                             style="width: {{ min(100, ($availableSlots / (5 * $table->capacity)) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="mb-6 overflow-x-auto pb-2">
            <div class="flex space-x-3 border-b-2 border-gray-200">
                <button wire:click="selectCategory('all')" 
                        class="px-5 py-3 text-sm font-semibold border-b-3 transition-all duration-200 whitespace-nowrap {{ $selectedCategory === 'all' ? 'border-indigo-600 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Todos
                    </span>
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category['id'] }})" 
                            class="px-5 py-3 text-sm font-semibold border-b-3 transition-all duration-200 whitespace-nowrap {{ $selectedCategory == $category['id'] ? 'border-indigo-600 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        @if(count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if($product['image'])
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <!-- Badge for type -->
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product['type'] === 'dish' ? 'bg-orange-500 text-white' : 'bg-blue-500 text-white' }}">
                                    {{ $product['type'] === 'dish' ? 'Plato' : 'Bebida' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-5">
                            <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-1">{{ $product['name'] }}</h3>
                            @if($product['description'])
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product['description'] }}</p>
                            @endif
                            
                            @if(!empty($product['allergens']))
                                <div class="mb-3 flex flex-wrap gap-1">
                                    @foreach($product['allergens'] as $allergen)
                                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                            {{ $allergen }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <div>
                                    <span class="text-2xl font-extrabold text-indigo-600">€{{ number_format($product['price'], 2) }}</span>
                                </div>
                                <button wire:click="$dispatch('add-to-cart', {id: {{ $product['id'] }}, type: '{{ $product['type'] }}', quantity: 1})" 
                                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Agregar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600">No hay productos disponibles</p>
                <p class="text-sm text-gray-500 mt-1">en esta categoría</p>
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


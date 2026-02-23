<x-layouts.staff title="Panel Camarero">

    <div class="max-w-7xl mx-auto py-12 px-6 space-y-12">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-extrabold text-red-600">
                Panel Staff 🍣
            </h1>

            <div class="text-sm text-gray-500">
                {{ now()->format('d M Y - H:i') }}
            </div>
        </div>

        {{-- ESTADO MESAS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'🪑','value'=>$totalTables,'label'=>'Totales','color'=>'border-gray-400'],
                ['icon'=>'💺','value'=>$occupiedTables,'label'=>'Ocupadas','color'=>'border-red-500'],
                ['icon'=>'🛋️','value'=>$freeTables,'label'=>'Libres','color'=>'border-green-500'],
                ['icon'=>'⏳','value'=>$reservedTables,'label'=>'Reservadas','color'=>'border-yellow-500'],
            ] as $stat)
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-t-4 {{ $stat['color'] }} hover:shadow-2xl transition">
                    <div class="text-3xl">{{ $stat['icon'] }}</div>
                    <p class="mt-2 text-3xl font-bold">{{ $stat['value'] }}</p>
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- CONTROL STOCK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- PLATOS --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">
                    🍣 Control Stock Platos
                </h2>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach($allDishes as $dish)
                        <form method="POST"
                              action="{{ route('staff.dishes.toggle', $dish) }}"
                              class="group flex justify-between items-center border-b pb-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-50 hover:shadow-md hover:scale-[1.01]">
                            @csrf
                            @method('PATCH')

                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-red-600 transition">
                                    {{ $dish->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $dish->category->name ?? 'Sin categoría' }}
                                </p>
                            </div>

                            <button class="px-3 py-1 text-xs font-semibold rounded-full transition-all duration-200 {{ $dish->available ? 'bg-green-100 text-green-700 group-hover:bg-green-200' : 'bg-red-100 text-red-700 group-hover:bg-red-200' }}">
                                {{ $dish->available ? 'Disponible' : 'Sin stock' }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- BEBIDAS --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">
                    🥂 Control Stock Bebidas
                </h2>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach($allDrinks as $drink)
                        <form method="POST" action="{{ route('staff.drinks.toggle', $drink) }}"
                              class="group flex justify-between items-center border-b pb-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-50 hover:shadow-md hover:scale-[1.01]">
                            @csrf
                            @method('PATCH')

                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-orange-600 transition">
                                    {{ $drink->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $drink->category->name ?? 'Sin categoría' }}
                                </p>
                            </div>

                            <button class="px-3 py-1 text-xs font-semibold rounded-full transition-all duration-200 {{ $drink->available ? 'bg-green-100 text-green-700 group-hover:bg-green-200' : 'bg-red-100 text-red-700 group-hover:bg-red-200' }}">
                                {{ $drink->available ? 'Disponible' : 'Sin stock' }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-layouts.staff>

<x-layouts.staff title="Mesa {{ $table->name }}">

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">
                Mesa {{ $table->name }}
            </h1>

            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @class([
                    'bg-green-100 text-green-700' => $table->status === 'available',
                    'bg-yellow-100 text-yellow-700' => $table->status === 'reserved',
                    'bg-red-100 text-red-700' => $table->status === 'occupied',
                ])">
                {{ ucfirst($table->status) }}
            </span>
        </div>

        {{-- Acciones --}}
        <div class="flex gap-3 flex-wrap">

            {{-- Mesa libre --}}
            @if ($table->status === 'available')

                <form method="POST" action="{{ route('staff-tables.occupy', $table) }}">
                    @csrf
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Ocupar mesa
                    </button>
                </form>

                <form method="POST" action="{{ route('staff-tables.reserve', $table) }}">
                    @csrf
                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                        Reservar mesa
                    </button>
                </form>

                {{-- Mesa reservada --}}
            @elseif ($table->status === 'reserved')

                <form method="POST" action="{{ route('staff-tables.occupy', $table) }}">
                    @csrf
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Ocupar mesa
                    </button>
                </form>

                <form method="POST" action="{{ route('staff-tables.cancel-reserve', $table) }}">
                    @csrf
                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Cancelar reserva
                    </button>
                </form>

                {{-- Mesa ocupada --}}
            @else

                <form method="POST" action="{{ route('staff-tables.free', $table) }}">
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Liberar mesa
                    </button>
                </form>

            @endif
        </div>


        {{-- Pedidos --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">
                Pedidos
            </h2>

            @forelse($table->orders as $order)
                <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                    <div class="flex justify-between mb-3">
                <span class="font-semibold">
                    Pedido #{{ $order->id }}
                </span>

                        <span class="text-sm text-gray-500">
                    {{ $order->date?->format('H:i') }}
                </span>
                    </div>

                    {{-- PLATOS --}}
                    @if($order->dishes->isNotEmpty())
                        <p class="font-medium text-gray-700 mb-1">🍽️ Platos</p>
                        <ul class="text-sm mb-2 space-y-1">
                            @foreach($order->dishes as $dish)
                                @php
                                    $menu = $table->menu ?? null;
                                    $menuDish = $menu ? $menu->dishes()->where('dish_id', $dish->id)->first() : null;
                                    $isSpecial = $menuDish?->pivot->is_special ?? false;
                                    $extraPrice = null;
                                    if ($menu && $isSpecial) {
                                        $extraPrice = $menuDish->pivot->custom_price ?? $dish->price;
                                    }
                                @endphp

                                <li class="flex justify-between">
                                    <span>
                                        {{ $dish->name }}
                                        <span class="text-gray-500">× {{ $dish->pivot->quantity }}</span>
                                    </span>
                                    <span>
                                        @if($menu)
                                            @if($isSpecial)
                                                {{ number_format($extraPrice * $dish->pivot->quantity, 2) }} €
                                            @else
                                                <span class="text-sm text-gray-500">Incluido en menú</span>
                                            @endif
                                        @else
                                            {{ number_format($dish->price * $dish->pivot->quantity, 2) }} €
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- BEBIDAS --}}
                    @if($order->drinks->isNotEmpty())
                        <p class="font-medium text-gray-700 mb-1">🥤 Bebidas</p>
                        <ul class="text-sm mb-3 space-y-1">
                            @php $drinkCount = 0; @endphp
                            @foreach($order->drinks as $drink)
                                @php
                                    $prev = $drinkCount;
                                    $drinkCount += $drink->pivot->quantity;
                                    $chargeable = max(0, min($drink->pivot->quantity, $drinkCount - 1 - $prev));
                                @endphp
                                <li class="flex justify-between">
                                    <span>
                                        {{ $drink->name }}
                                        <span class="text-gray-500">× {{ $drink->pivot->quantity }}</span>
                                    </span>
                                    <span>
                                        {{ number_format($drink->price * $chargeable, 2) }} €
                                        @if($chargeable < $drink->pivot->quantity)
                                            <span class="text-sm text-gray-500"> ({{ $chargeable }} cobradas)</span>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- TOTAL --}}
                    <div class="pt-3 border-t text-right font-bold">
                        Total: {{ number_format($order->calculateTotal(), 2) }} €
                    </div>
                </div>
            @empty
                {{-- EMPTY STATE --}}
                <div class="text-center py-10 text-gray-500">
                    <svg class="mx-auto h-12 w-12 mb-3 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h18v6H3zM3 9h18v12H3z"/>
                    </svg>

                    <p class="text-sm">
                        Esta mesa todavía no tiene pedidos.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Volver --}}
        <div>
            <a href="{{ route('staff-tables.index') }}"
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                ← Volver a mesas
            </a>
        </div>

    </div>

</x-layouts.staff>

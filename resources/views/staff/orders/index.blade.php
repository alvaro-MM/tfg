<x-layouts.staff title="Pedidos activos">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">🍳 Pedidos activos</h1>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Sin pedidos --}}
        @if($orders->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-gray-500 text-center">
                No hay pedidos pendientes.
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow p-6 border">

                        {{-- Header --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold">
                                    Mesa {{ $order->table->name }}
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Pedido #{{ $order->id }}
                                    · {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Badge estado --}}
                            <span
                                @class([
                                    'px-3 py-1 rounded-full text-sm font-semibold',
                                    'bg-yellow-100 text-yellow-800' => $order->status === 'pending',
                                    'bg-blue-100 text-blue-800' => $order->status === 'preparing',
                                    'bg-green-100 text-green-800' => $order->status === 'served',
                                ])
                            >
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Contenido --}}
                        <div class="mt-4 grid md:grid-cols-2 gap-6">

                            {{-- Platos --}}
                            <div>
                                <h3 class="font-semibold mb-2">🍽️ Platos</h3>

                                @if($order->dishes->isEmpty())
                                    <p class="text-sm text-gray-500">Sin platos.</p>
                                @else
                                    <ul class="space-y-1 text-sm">
                                        @foreach($order->dishes as $dish)
                                            <li class="flex justify-between">
                                                <span>
                                                    {{ $dish->name }}
                                                    <span class="text-gray-500">
                                                        × {{ $dish->pivot->quantity }}
                                                    </span>
                                                </span>

                                                @if(isset($dish->available) && ! $dish->available)
                                                    <span class="text-red-600 text-xs font-semibold">
                                                        AGOTADO
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            {{-- Bebidas --}}
                            <div>
                                <h3 class="font-semibold mb-2">🥤 Bebidas</h3>

                                @if($order->drinks->isEmpty())
                                    <p class="text-sm text-gray-500">Sin bebidas.</p>
                                @else
                                    <ul class="space-y-1 text-sm">
                                        @foreach($order->drinks as $drink)
                                            <li>
                                                {{ $drink->name }}
                                                <span class="text-gray-500">
                                                    × {{ $drink->pivot->quantity }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="mt-6 flex gap-3">
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('staff.orders.prepare', $order) }}">
                                    @csrf
                                    <button
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    >
                                        Preparar
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'preparing')
                                <form method="POST" action="{{ route('staff.orders.serve', $order) }}">
                                    @csrf
                                    <button
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                    >
                                        Servir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.staff>

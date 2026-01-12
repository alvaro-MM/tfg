<x-layouts.app title="Pedidos activos">
    <h1 class="text-3xl font-bold mb-6">Pedidos activos</h1>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <p class="text-gray-500">No hay pedidos pendientes.</p>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="border rounded-lg p-4 bg-white shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold">
                                Mesa {{ $order->table->name }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                Pedido #{{ $order->id }} · {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @class([
                                'bg-yellow-100 text-yellow-800' => $order->status === 'pending',
                                'bg-blue-100 text-blue-800' => $order->status === 'preparing',
                                'bg-green-100 text-green-800' => $order->status === 'served',
                            ])">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    {{-- Items del pedido --}}
                    <div class="mt-4">
                        <h3 class="font-semibold mb-2">Platos</h3>
                        <ul class="space-y-1">
                            @foreach($order->items as $item)
                                <li class="flex justify-between text-sm">
                                    <span>
                                        {{ $item->dish->name }}
                                        <span class="text-gray-500">x{{ $item->quantity }}</span>
                                    </span>

                                    @if($item->dish->stock <= 0)
                                        <span class="text-red-600 text-xs font-semibold">
                                            AGOTADO
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Acciones --}}
                    <div class="mt-4 flex gap-2">
                        @if($order->status === 'pending')
                            <form method="POST" action="{{ route('staff.orders.prepare', $order) }}">
                                @csrf
                                <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Preparar
                                </button>
                            </form>
                        @endif

                        @if($order->status === 'preparing')
                            <form method="POST" action="{{ route('staff.orders.serve', $order) }}">
                                @csrf
                                <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                    Servir
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>

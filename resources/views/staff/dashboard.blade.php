<x-layouts.app title="Panel Camarero">
    <h1 class="text-3xl font-bold mb-6">Panel de Camarero</h1>

    <div class="grid grid-cols-4 gap-4">
        <div class="bg-green-100 p-4 rounded">Mesas libres: {{ $freeTables }}</div>
        <div class="bg-red-100 p-4 rounded">Mesas ocupadas: {{ $occupiedTables }}</div>
        <div class="bg-yellow-100 p-4 rounded">Pedidos pendientes: {{ $pendingOrders }}</div>
    </div>

    <h2 class="mt-8 text-xl font-semibold">Platos agotados</h2>
    <ul class="mt-2">
        @forelse($outOfStockDishes as $dish)
            <li class="text-red-600">{{ $dish->name }}</li>
        @empty
            <li class="text-gray-500">Todos disponibles</li>
        @endforelse
    </ul>
</x-layouts.app>

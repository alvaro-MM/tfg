<x-layouts.staff title="Panel Camarero">

    <div class="max-w-7xl mx-auto py-16 px-6">
        <h1 class="text-4xl font-extrabold text-center text-red-600 mb-12">
            Dashboard Staff
        </h1>

        {{-- Estadísticas de Mesas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="dashboard-card">
                <div class="text-3xl">🪑</div>
                <p class="mt-2 text-3xl font-bold">{{ $totalTables }}</p>
                <p class="text-sm text-gray-500">Mesas Totales</p>
            </div>

            <div class="dashboard-card">
                <div class="text-3xl">💺</div>
                <p class="mt-2 text-3xl font-bold">{{ $occupiedTables }}</p>
                <p class="text-sm text-gray-500">Mesas Ocupadas</p>
            </div>

            <div class="dashboard-card">
                <div class="text-3xl">🛋️</div>
                <p class="mt-2 text-3xl font-bold">{{ $freeTables }}</p>
                <p class="text-sm text-gray-500">Mesas Libres</p>
            </div>

            <div class="dashboard-card">
                <div class="text-3xl">⏳</div>
                <p class="mt-2 text-3xl font-bold">{{ $reservedTables }}</p>
                <p class="text-sm text-gray-500">Mesas Reservadas</p>
            </div>
        </div>

        {{-- Platos fuera de stock --}}
        @if($outOfStockDishes->count())
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">❌ Platos sin Stock</h2>
                <ul class="list-disc list-inside text-gray-700">
                    @foreach($outOfStockDishes as $dish)
                        <li>{{ $dish->name }} ({{ $dish->category->name ?? 'Sin categoría' }})</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <style>
        .dashboard-card {
            @apply bg-white rounded-xl shadow p-6 text-center border-t-4 border-red-500;
        }
    </style>

</x-layouts.staff>

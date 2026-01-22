<x-layouts.admin :title="'Dashboard Rendimiento General'">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            Rendimiento general
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Última actualización: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios totales</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $totalUsers }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos totales</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $totalOrders }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Reseñas totales</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $totalReviews ?? 0 }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios últimos 30 días</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $activeUsersLast30 ?? 0 }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Usuarios registrados últimos 7 días</h2>
            <canvas id="usersChart" height="150" data-labels='@json($chartLabels)' data-data='@json($chartData)'></canvas>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Pedidos últimos 7 días</h2>
            <canvas id="ordersChart" height="150" data-labels='@json($ordersChartLabels ?? [])' data-data='@json($ordersChartData ?? [])'></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Top platos más pedidos</h2>
            <ul class="text-sm space-y-2">
                @forelse($topDishes ?? [] as $dish)
                <li>{{ $dish->dish->name ?? '—' }} ({{ $dish->total }} pedidos)</li>
                @empty
                <li>No hay datos de platos</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Top bebidas más pedidas</h2>
            <ul class="text-sm space-y-2">
                @forelse($topDrinks ?? [] as $drink)
                <li>{{ $drink->drink->name ?? '—' }} ({{ $drink->total }} pedidos)</li>
                @empty
                <li>No hay datos de bebidas</li>
                @endforelse
            </ul>
        </div>

    </div>

    <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg bg-blue-50 p-6 shadow dark:bg-blue-900 dark:text-white">
            <h3 class="mb-2 text-md font-semibold">Crecimiento usuarios</h3>
            <p class="text-2xl font-bold">{{ $userGrowth ?? '—' }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-300">vs semana anterior</p>
        </div>
        <div class="rounded-lg bg-green-50 p-6 shadow dark:bg-green-900 dark:text-white">
            <h3 class="mb-2 text-md font-semibold">Crecimiento pedidos</h3>
            <p class="text-2xl font-bold">{{ $ordersGrowth ?? '—' }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-300">vs semana anterior</p>
        </div>
        <div class="rounded-lg bg-yellow-50 p-6 shadow dark:bg-yellow-900 dark:text-white">
            <h3 class="mb-2 text-md font-semibold">Crecimiento reseñas</h3>
            <p class="text-2xl font-bold">{{ $reviewsGrowth ?? '—' }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-300">vs semana anterior</p>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush
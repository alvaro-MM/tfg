<x-layouts.admin :title="'Dashboard Admin'">

    <div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            Resumen del día
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ now()->format('d/m/Y') }}
        </p>
    </div>

    <a href="{{ route('admin.pdf.daily-performance') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-stone-900 px-4 py-2 text-sm font-medium text-white
              hover:bg-stone-800 dark:bg-stone-700 dark:hover:bg-stone-600 transition">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>

        Descargar PDF
    </a>
</div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $usersToday }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $ordersToday }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Reseñas hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $reviewsToday }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">% Ocupación mesas</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $tablesOccupationPercent }}%</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos activos</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">{{ $activeOrders }}</p>
        </div>

    </div>

    @if($alerts->isNotEmpty())
    <div class="mb-8">
        @foreach($alerts as $alert)
        <div class="mb-2 rounded-lg bg-red-100 p-4 text-red-800 dark:bg-red-900 dark:text-red-200">
            {{ $alert }}
        </div>
        @endforeach
    </div>
    @endif

    <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Estado de mesas</h2>
            <ul class="space-y-2 text-sm">
                <li class="flex justify-between">
                    <span>Disponibles</span>
                    <span class="font-semibold text-green-600">{{ $availableTables }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Ocupadas</span>
                    <span class="font-semibold text-red-600">{{ $occupiedTables }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Reservadas</span>
                    <span class="font-semibold text-yellow-600">{{ $reservedTables }}</span>
                </li>
                <li class="flex justify-between border-t pt-2 mt-2">
                    <span>Total</span>
                    <span class="font-bold">{{ $totalTables }}</span>
                </li>
            </ul>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 lg:col-span-2">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Pedidos por hora</h2>
            <canvas id="ordersHourChart" height="120"
                data-labels='@json($ordersPerHourLabels)'
                data-data='@json($ordersPerHourData)'>
            </canvas>
        </div>
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 mb-8">
        <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Últimos usuarios registrados</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b dark:border-stone-700">
                    <th class="py-2 text-left">Nombre</th>
                    <th class="py-2 text-left">Email</th>
                    <th class="py-2 text-left">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestUsers as $user)
                <tr class="border-b dark:border-stone-700">
                    <td class="py-2">{{ $user->name }}</td>
                    <td class="py-2">{{ $user->email }}</td>
                    <td class="py-2 text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-gray-500">No hay usuarios recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 mb-8">
        <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Últimos pedidos</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b dark:border-stone-700">
                    <th class="py-2 text-left">Pedido</th>
                    <th class="py-2 text-left">Usuario</th>
                    <th class="py-2 text-left">Mesa</th>
                    <th class="py-2 text-left">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                <tr class="border-b dark:border-stone-700">
                    <td class="py-2">{{ $order->name }}</td>
                    <td class="py-2">{{ $order->user->name ?? '—' }}</td>
                    <td class="py-2">{{ $order->table->name ?? '—' }}</td>
                    <td class="py-2 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">No hay pedidos recientes</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 mb-8">
        <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Últimas reseñas hoy</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b dark:border-stone-700">
                    <th class="py-2 text-left">Usuario</th>
                    <th class="py-2 text-left">Plato</th>
                    <th class="py-2 text-left">Bebida</th>
                    <th class="py-2 text-left">Título</th>
                    <th class="py-2 text-left">Hora</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestReviewsToday as $review)
                <tr class="border-b dark:border-stone-700">
                    <td class="py-2">{{ $review->user->name ?? '—' }}</td>
                    <td class="py-2">{{ $review->dish->name ?? '—' }}</td>
                    <td class="py-2">{{ $review->drink->name ?? '—' }}</td>
                    <td class="py-2">{{ $review->name }}</td>
                    <td class="py-2 text-gray-500">{{ $review->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">No hay reseñas hoy</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Top platos reseñados hoy</h2>
            <ul class="text-sm space-y-2">
                @forelse($topDishesToday as $dish)
                <li>{{ $dish->dish->name ?? '—' }} ({{ $dish->total }} reseñas)</li>
                @empty
                <li>No hay reseñas de platos hoy</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">Top bebidas reseñadas hoy</h2>
            <ul class="text-sm space-y-2">
                @forelse($topDrinksToday as $drink)
                <li>{{ $drink->drink->name ?? '—' }} ({{ $drink->total }} reseñas)</li>
                @empty
                <li>No hay reseñas de bebidas hoy</li>
                @endforelse
            </ul>
        </div>
    </div>

</x-layouts.admin>

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush
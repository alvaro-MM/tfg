<x-layouts.admin :title="'Dashboard Admin'">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            Resumen del día
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ now()->format('d/m/Y') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios registrados hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ $usersToday }}
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios totales</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ $totalUsers }}
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ $ordersToday }}
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos totales</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ $totalOrders }}
            </p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800">
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">
                Estado de mesas
            </h2>
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
            <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">
                Últimos usuarios registrados
            </h2>
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
                        <td class="py-2 text-gray-500">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">
                            No hay usuarios recientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 mb-8">
        <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">
            Últimos pedidos
        </h2>
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
                    <td class="py-2 text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">
                        No hay pedidos recientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
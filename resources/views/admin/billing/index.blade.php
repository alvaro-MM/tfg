<x-layouts.admin :title="'Dashboard de Facturación'">

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
                Facturación
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Última actualización: {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <a href="{{ route('invoices.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5
              text-sm font-medium text-white hover:bg-indigo-700 transition">
            Ver todas las facturas
        </a>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Hoy</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ number_format($stats['today'], 2) }} €
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Esta semana</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ number_format($stats['week'], 2) }} €
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Este mes</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ number_format($stats['month'], 2) }} €
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow dark:bg-stone-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">Este año</p>
            <p class="mt-2 text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ number_format($stats['year'], 2) }} €
            </p>
        </div>

    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-stone-800 mb-8 h-80 relative">

        <h2 class="mb-4 text-lg font-semibold text-stone-900 dark:text-stone-100">
            Facturación mensual ({{ now()->year }})
        </h2>

        <canvas
            id="billingChart"
            data-labels='@json($chartLabels)'
            data-data='@json($chartData)'>
        </canvas>

    </div>

</x-layouts.admin>

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush
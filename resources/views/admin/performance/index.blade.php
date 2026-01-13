<x-layouts.admin :title="'Dashboard Admin'">
    <div class="mb-8 rounded-lg bg-white p-6 shadow dark:bg-stone-800">
        <h2 class="mb-4 text-xl font-semibold text-stone-900 dark:text-stone-100">
            Usuarios registrados en los últimos 7 días
        </h2>
        <canvas
            id="usersChart"
            height="120"
            data-labels='@json($chartLabels)'
            data-data='@json($chartData)'>
        </canvas>
    </div>
</x-layouts.admin>
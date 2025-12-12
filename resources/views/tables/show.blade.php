<x-layouts.app :title="'Mesa ' . $table->name">

    <h1 class="text-2xl font-semibold mb-4">Mesa {{ $table->name }}</h1>

    <ul class="space-y-2">
        <li><strong>Capacidad:</strong> {{ $table->capacity }}</li>
        <li><strong>Estado:</strong> {{ ucfirst($table->status) }}</li>
        <li><strong>Notas:</strong> {{ $table->notes }}</li>
        <li><strong>Usuario asignado:</strong> {{ optional($table->user)->name ?? '—' }}</li>
        <li><strong>Menú:</strong> {{ optional($table->menu)->name ?? '—' }}</li>
    </ul>

    <a href="{{ route('tables.index') }}" class="mt-4 inline-block text-gray-600">Volver</a>

</x-layouts.app>

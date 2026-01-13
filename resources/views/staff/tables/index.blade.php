<x-layouts.app title="Mesas">
    <h1 class="text-2xl font-bold mb-4">Mesas</h1>

    <div class="grid grid-cols-4 gap-4">
        @foreach($tables as $table)
            <div class="p-4 rounded border
                {{ $table->status === 'available' ? 'bg-green-100' : 'bg-red-100' }}">
                <h2 class="font-bold">{{ $table->name }}</h2>
                <p>Estado: {{ ucfirst($table->status) }}</p>

                <a href="{{ route('staff.tables.show', $table) }}"
                   class="text-blue-600 underline">Ver</a>
            </div>
        @endforeach
    </div>
</x-layouts.app>

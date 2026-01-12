<x-layouts.app title="Mesa {{ $table->name }}">
    <h1 class="text-2xl font-bold mb-4">Mesa {{ $table->name }}</h1>

    <p>Estado: {{ $table->status }}</p>

    @if($table->status === 'available')
        <form method="POST" action="{{ route('staff.tables.occupy', $table) }}">
            @csrf
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Ocupar mesa
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('staff.tables.free', $table) }}">
            @csrf
            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Liberar mesa
            </button>
        </form>
    @endif
</x-layouts.app>

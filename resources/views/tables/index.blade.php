<x-layouts.app :title="'Mesas'">

    <h1 class="text-2xl font-semibold mb-4">Mesas</h1>

    <a href="{{ route('tables.create') }}"
       class="mb-4 inline-block bg-green-600 text-white px-4 py-2 rounded">
        Nueva Mesa
    </a>

    <table class="w-full rounded border border-gray-300">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Nombre</th>
            <th class="p-2">Capacidad</th>
            <th class="p-2">Estado</th>
            <th class="p-2">QR / Menú</th>
            <th class="p-2">Acciones</th>
        </tr>
        </thead>

        <tbody>
        @foreach($tables as $table)
            <tr class="border-b">
                <td class="p-2">{{ $table->name }}</td>
                <td class="p-2">{{ $table->capacity }}</td>
                <td class="p-2">{{ ucfirst($table->status) }}</td>
                <td class="p-2">
                    @if($table->qr_token)
                        @php($qrUrl = route('public.menu', $table->qr_token))
                        <div class="flex flex-col gap-1">
                            <a href="{{ $qrUrl }}" target="_blank" class="text-indigo-600 text-sm underline">
                                Abrir menú
                            </a>
                            <span class="text-xs text-gray-500 break-all">
                                {{ $qrUrl }}
                            </span>
                        </div>
                    @else
                        <span class="text-xs text-gray-500">Sin QR asignado</span>
                    @endif
                </td>
                <td class="p-2 space-x-2">
                    <a href="{{ route('tables.edit', $table) }}" class="text-blue-600">Editar</a>
                    <a href="{{ route('tables.show', $table) }}" class="text-purple-600">Ver</a>

                    <form action="{{ route('tables.destroy', $table) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('¿Eliminar?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-layouts.app>

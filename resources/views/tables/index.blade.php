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
                        <div class="flex items-center gap-3">
                            <!-- QR Code Image -->
                            <div class="bg-white p-2 rounded border border-gray-300 flex-shrink-0">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($qrUrl) }}" 
                                     alt="QR Code Mesa {{ $table->name }}" 
                                     class="w-20 h-20"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2280%22%3E%3Crect fill=%22%23ccc%22 width=%2280%22 height=%2280%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22%3EQR%3C/text%3E%3C/svg%3E'">
                            </div>
                            <div class="flex flex-col gap-1 min-w-0">
                                <a href="{{ $qrUrl }}" target="_blank" class="text-indigo-600 text-sm font-medium hover:underline">
                                    Abrir menú
                                </a>
                                <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrUrl) }}" 
                                   download="qr-mesa-{{ $table->name }}.png"
                                   class="text-xs text-gray-600 hover:text-gray-900 underline">
                                    Descargar QR
                                </a>
                                <span class="text-xs text-gray-500 break-all">
                                    {{ $qrUrl }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col gap-2">
                            <span class="text-xs text-gray-500">Sin QR asignado</span>
                            <form action="{{ route('tables.generate-qr', $table) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Generar QR
                                </button>
                            </form>
                        </div>
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

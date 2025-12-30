<x-layouts.app :title="'Mesa ' . $table->name">

    <h1 class="text-2xl font-semibold mb-4">Mesa {{ $table->name }}</h1>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <h2 class="text-lg font-semibold mb-3">Datos generales</h2>
            <ul class="space-y-2">
                <li><strong>Capacidad:</strong> {{ $table->capacity }}</li>
                <li><strong>Estado:</strong> {{ ucfirst($table->status) }}</li>
                <li><strong>Notas:</strong> {{ $table->notes }}</li>
                <li><strong>Usuario asignado:</strong> {{ optional($table->user)->name ?? '—' }}</li>
                <li><strong>Menú:</strong> {{ optional($table->menu)->name ?? '—' }}</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-3">Acceso QR / Menú público</h2>

            @if($table->qr_token)
                @php
                    $qrUrl = route('public.menu', $table->qr_token);
                @endphp

                <p class="mb-2">
                    <strong>URL del menú para esta mesa:</strong><br>
                    <a href="{{ $qrUrl }}" target="_blank" class="text-indigo-600 underline break-all">
                        {{ $qrUrl }}
                    </a>
                </p>

                <p class="text-sm text-gray-600 mb-3">
                    Esta es la URL que se codifica en el QR de la mesa. Desde aquí los clientes pueden
                    ver el menú, añadir platos/bebidas al carrito y generar pedidos buffet.
                </p>

                <a href="{{ $qrUrl }}" target="_blank"
                   class="inline-flex items-center rounded bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">
                    Abrir menú público de la mesa
                </a>
            @else
                <p class="text-sm text-gray-600">
                    Esta mesa aún no tiene un token QR asignado. Asígnale un menú y genera el QR desde la lógica que prefieras.
                </p>
            @endif
        </div>
    </div>

    <a href="{{ route('tables.index') }}" class="mt-6 inline-block text-gray-600 hover:text-gray-900">
        Volver al listado de mesas
    </a>

</x-layouts.app>

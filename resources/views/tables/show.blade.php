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

                <!-- QR Code Display -->
                <div class="bg-white p-6 rounded-lg border-2 border-gray-200 shadow-md mb-4">
                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Código QR para escanear</h3>
                        <div class="bg-white p-4 rounded-lg border-2 border-gray-300 shadow-lg">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrUrl) }}" 
                                 alt="QR Code Mesa {{ $table->name }}" 
                                 class="w-64 h-64"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22250%22 height=%22250%22%3E%3Crect fill=%22%23ccc%22 width=%22250%22 height=%22250%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22 font-size=%2220%22%3EQR Code%3C/text%3E%3C/svg%3E'">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Escanea este código con tu móvil para acceder al menú
                        </p>
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($qrUrl) }}" 
                           download="qr-mesa-{{ $table->name }}.png"
                           class="mt-3 inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Descargar QR (alta resolución)
                        </a>
                    </div>
                </div>

                <!-- URL Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        URL del menú para esta mesa:
                    </p>
                    <a href="{{ $qrUrl }}" target="_blank" class="text-indigo-600 underline break-all text-sm">
                        {{ $qrUrl }}
                    </a>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Esta es la URL que se codifica en el QR de la mesa. Desde aquí los clientes pueden
                    ver el menú, añadir platos/bebidas al carrito y generar pedidos buffet.
                </p>

                <div class="flex gap-2">
                    <a href="{{ $qrUrl }}" target="_blank"
                       class="inline-flex items-center rounded bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Abrir menú público
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center rounded bg-gray-200 px-4 py-2 text-gray-800 text-sm font-medium hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir QR
                    </button>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800 mb-3">
                        Esta mesa aún no tiene un token QR asignado.
                    </p>
                    <form action="{{ route('tables.generate-qr', $table) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Generar Token QR
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('tables.index') }}" class="mt-6 inline-block text-gray-600 hover:text-gray-900">
        Volver al listado de mesas
    </a>

</x-layouts.app>

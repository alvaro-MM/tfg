@extends('layout.tfg')

@section('title', 'Dashboard Cliente')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="flex flex-col gap-10">

            {{-- Header --}}
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    👋 Bienvenido, {{ auth()->user()->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl">
                    Desde aquí puedes reservar mesas y consultar tus pedidos recientes.
                </p>
            </div>

            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow border">
                    <p class="text-sm text-gray-500">Mesas totales</p>
                    <p class="text-3xl font-bold">{{ $totalTables }}</p>
                </div>

                <div class="bg-green-50 dark:bg-gray-800 rounded-2xl p-6 shadow border">
                    <p class="text-sm text-gray-500">Disponibles</p>
                    <p class="text-3xl font-bold text-green-600">{{ $availableTables }}</p>
                </div>

                <div class="bg-red-50 dark:bg-gray-800 rounded-2xl p-6 shadow border">
                    <p class="text-sm text-gray-500">Ocupadas</p>
                    <p class="text-3xl font-bold text-red-600">{{ $occupiedTables }}</p>
                </div>
            </div>

            {{-- Gráfica pedidos --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow border">
                <h2 class="text-xl font-semibold mb-4">📊 Tus pedidos recientes</h2>
                <canvas id="ordersChart" height="90"></canvas>
            </div>

            {{-- Accesos rápidos --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">⚡ Accesos rápidos</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('bookings.create') }}"
                       class="bg-blue-50 hover:bg-blue-100 dark:bg-gray-800 rounded-2xl p-6 shadow transition">
                        <p class="font-semibold text-blue-600">🍽️ Reservar mesa</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Consulta disponibilidad y haz tu reserva en segundos.
                        </p>
                    </a>

                    <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-6 shadow opacity-80">
                        <p class="font-semibold">🧾 Mis pedidos</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Historial de pedidos realizados.
                        </p>
                    </div>

                    <a  href="{{ route('review.create') }}"
                       class="bg-yellow-50 hover:bg-yellow-100 dark:bg-gray-800 rounded-2xl p-6 shadow transition">
                        <p class="font-semibold text-yellow-600">⭐ Escribir reseña</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Valora tu experiencia con el restaurante.
                        </p>
                    </a>
                </div>
            </div>

            {{-- Últimos pedidos --}}
            <div>
                <h2 class="text-xl font-semibold mb-4">🧾 Últimos pedidos</h2>

                @forelse($recentOrders as $order)
                    <div class="bg-white dark:bg-gray-800 border rounded-2xl p-5 shadow mb-3 flex justify-between">
                        <div>
                            <p class="font-semibold">Pedido #{{ $order->id }}</p>
                            <p class="text-sm text-gray-500">
                                Mesa: {{ $order->table->name ?? 'N/A' }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-400">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">No has realizado pedidos aún.</p>
                @endforelse
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">⭐ Tus reseñas</h2>

                @forelse($recentReviews as $review)
                    <div class="bg-white dark:bg-gray-800 border rounded-2xl p-5 shadow mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-semibold">{{$review->name}}</p>
                            <p class="text-sm text-gray-400">
                                {{ $review->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        {{-- Estrellas --}}
                        <div class="flex gap-1 text-yellow-400 mb-2">
                            @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                ⭐
                            @endfor
                        </div>

                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            {{ $review->description ?? 'Sin descripcion.' }}
                        </p>

                        @if($review->image)
                            <img
                                src="{{ asset('storage/' . $review->image) }}"
                                alt="Imagen review"
                                class="rounded-xl w-full h-64 object-cover shadow-md p-4"
                            >
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('review.show', $review) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700
                              text-white text-sm font-medium rounded-lg shadow transition duration-200">
                                Ver detalle →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 text-center text-gray-500">
                        Aún no has escrito ninguna reseña ✨
                        <br>
                        <span class="text-sm">¡Tu opinión ayuda mucho!</span>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ordersChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ordersPerDay->pluck('day')) !!},
                datasets: [{
                    label: 'Pedidos',
                    data: {!! json_encode($ordersPerDay->pluck('total')) !!},
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection

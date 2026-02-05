@extends('layout.tfg')

@section('title', 'Inicio')

@section('content')

    {{-- HERO --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-red-600 via-red-500 to-orange-400">
        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 text-center text-white">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight drop-shadow">
                Sushi Buffet 🍣
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-lg text-white/90">
                Buffet japonés ilimitado con gestión moderna de mesas, pedidos y menú.
            </p>

            <div class="mt-10 flex justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="rounded-xl bg-white px-6 py-3 font-semibold text-red-600 shadow-lg hover:scale-105 transition">
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-xl bg-white px-6 py-3 font-semibold text-red-600 shadow-lg hover:scale-105 transition">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-xl border border-white px-6 py-3 font-semibold hover:bg-white hover:text-red-600 transition">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- QUÉ OFRECEMOS --}}
    <div class="max-w-7xl mx-auto py-20 px-6">
        <h2 class="text-4xl font-bold text-center text-gray-900 dark:text-white">
            Gestión completa del restaurante
        </h2>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="rounded-2xl bg-white dark:bg-neutral-800 p-8 shadow hover:shadow-xl transition">
                <div class="text-4xl">🍣</div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                    Menú y Platos
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Control total de platos, bebidas, precios y alérgenos.
                </p>
            </div>

            <div class="rounded-2xl bg-white dark:bg-neutral-800 p-8 shadow hover:shadow-xl transition">
                <div class="text-4xl">🪑</div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                    Mesas y Pedidos
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Gestión en tiempo real de ocupación y pedidos.
                </p>
            </div>

            <div class="rounded-2xl bg-white dark:bg-neutral-800 p-8 shadow hover:shadow-xl transition">
                <div class="text-4xl">📊</div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                    Ventas y Opiniones
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Facturación, reseñas y estadísticas.
                </p>
            </div>

        </div>
    </div>

    {{-- STATS --}}
    @auth
        <div class="bg-gray-100 dark:bg-neutral-900 py-16">
            <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

                <div class="rounded-xl bg-white dark:bg-neutral-800 p-6 shadow">
                    <div class="text-3xl">🍣</div>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Dish::count() }}
                    </p>
                    <p class="text-sm text-gray-500">Platos</p>
                </div>

                <div class="rounded-xl bg-white dark:bg-neutral-800 p-6 shadow">
                    <div class="text-3xl">🪑</div>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Table::count() }}
                    </p>
                    <p class="text-sm text-gray-500">Mesas</p>
                </div>

{{--                <div class="rounded-xl bg-white dark:bg-neutral-800 p-6 shadow">--}}
{{--                    <div class="text-3xl">⭐</div>--}}
{{--                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">--}}
{{--                        {{ number_format(\App\Models\Review::avg('rating'), 1) ?? '—' }}--}}
{{--                    </p>--}}
{{--                    <p class="text-sm text-gray-500">Valoración media</p>--}}
{{--                </div>--}}

                <div class="rounded-xl bg-white dark:bg-neutral-800 p-6 shadow">
                    <div class="text-3xl">⭐</div>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Review::count() }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Reseñas de platos y bebidas
                    </p>
                </div>


                <div class="rounded-xl bg-white dark:bg-neutral-800 p-6 shadow">
                    <div class="text-3xl">📄</div>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Invoice::count() }}
                    </p>
                    <p class="text-sm text-gray-500">Facturas</p>
                </div>

            </div>
        </div>
    @endauth

    {{-- PANEL RÁPIDO --}}
    @auth
        <div class="max-w-7xl mx-auto py-20 px-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-10">
                Accesos rápidos
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('dishes.index') }}"
                   class="group rounded-2xl bg-white dark:bg-neutral-800 p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="text-4xl">🍣</div>
                    <h3 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white group-hover:text-green-600">
                        Platos
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Crear, editar y gestionar platos.
                    </p>
                </a>

                <a href="{{ route('tables.index') }}"
                   class="group rounded-2xl bg-white dark:bg-neutral-800 p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="text-4xl">🪑</div>
                    <h3 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600">
                        Mesas
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Control de ocupación y reservas.
                    </p>
                </a>

                <a href="{{ route('menus.index') }}"
                   class="group rounded-2xl bg-white dark:bg-neutral-800 p-6 shadow hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="text-4xl">📋</div>
                    <h3 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600">
                        Menús
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Gestión de menús y combinaciones.
                    </p>
                </a>

            </div>
        </div>
    @endauth

@endsection

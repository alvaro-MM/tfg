@extends('layout.tfg')

@section('title', 'Inicio')

@section('content')

    <div class="max-w-7xl mx-auto text-center pt-20 pb-10">

        <h1 class="text-5xl font-extrabold text-red-600 drop-shadow-lg">
            ¡Bienvenido a Sushi Buffet!
        </h1>

        <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">
            Disfruta del buffet libre japonés más completo de la ciudad.
        </p>

        <!-- Secciones -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
            @auth
            <a href="{{ route('dashboard') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <p class="mt-1 text-sm">Accede al panel de control del sistema.</p>
            </a>
            @else
            <a href="{{ route('login') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Iniciar Sesión</h2>
                <p class="mt-1 text-sm">Accede a tu cuenta para gestionar el sistema.</p>
            </a>
            @endauth

            @auth
            <a href="{{ route('review.index') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Reseñas</h2>
                <p class="mt-1 text-sm">Lee opiniones o deja la tuya.</p>
            </a>
            @else
            <a href="{{ route('register') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Registrarse</h2>
                <p class="mt-1 text-sm">Crea una cuenta nueva para comenzar.</p>
            </a>
            @endauth

            @auth
            <a href="{{ route('tables.index') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Mesas</h2>
                <p class="mt-1 text-sm">Gestiona las mesas del restaurante.</p>
            </a>
            @else
            <a href="{{ route('login') }}"
               class="sushi-card">
                <h2 class="text-2xl font-bold">Acceso</h2>
                <p class="mt-1 text-sm">Inicia sesión para acceder a todas las funciones.</p>
            </a>
            @endauth
        </div>
    </div>

    <style>
        .sushi-card {
            @apply bg-white dark:bg-gray-800 shadow-lg p-6 rounded-xl
            hover:scale-110 hover:shadow-2xl transition text-red-600 text-center;
        }
    </style>

    @auth
    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4">
            <div class="rounded-xl bg-white p-8 shadow-md dark:bg-neutral-900">

                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        Panel de Gestión
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Acceso rápido a todas las secciones de gestión del sistema.
                    </p>
                </div>

                {{-- Cards --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Categorías --}}
                    <a href="{{ route('categories.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-indigo-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-indigo-600">
                            Categorías
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Gestiona las categorías de platos y bebidas.
                        </p>
                    </a>

                    {{-- Platos --}}
                    <a href="{{ route('dishes.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-green-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-green-600">
                            Platos
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Crear, editar y revisar los platos del menú.
                        </p>
                    </a>

                    {{-- Alérgenos --}}
                    <a href="{{ route('allergens.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-red-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-red-600">
                            Alérgenos
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Control de alérgenos asociados a productos.
                        </p>
                    </a>

                    {{-- Reviews --}}
                    <a href="{{ route('review.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-yellow-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-yellow-600">
                            Reviews
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Gestión de opiniones de platos y bebidas.
                        </p>
                    </a>

                    {{-- Bebidas --}}
                    <a href="{{ route('drinks.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-blue-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600">
                            Bebidas
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Administración de las bebidas disponibles.
                        </p>
                    </a>

                    {{-- Mesas --}}
                    <a href="{{ route('tables.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-blue-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600">
                            Mesas
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Administración de las mesas disponibles.
                        </p>
                    </a>

                    {{-- Menús --}}
                    <a href="{{ route('menus.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-purple-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-purple-600">
                            Menús
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Gestión de menús y combinaciones de platos.
                        </p>
                    </a>

                    {{-- Ofertas --}}
                    <a href="{{ route('offers.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-pink-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-pink-600">
                            Ofertas
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Administración de descuentos y promociones.
                        </p>
                    </a>

                    {{-- Facturas --}}
                    <a href="{{ route('invoices.index') }}"
                       class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-cyan-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-cyan-600">
                            Facturas
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Gestión de facturas y cobros.
                        </p>
                    </a>

                </div>
            </div>
        </div>
    </div>
    @endauth

@endsection

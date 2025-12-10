<x-layouts.app :title="__('Inicio')">
    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4">
            <div class="rounded-xl bg-white p-8 shadow-md dark:bg-neutral-900">

                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        Bienvenido al TFG
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Panel de control con acceso rápido a la gestión del sistema.
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

                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
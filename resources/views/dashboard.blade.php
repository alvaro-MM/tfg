<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                Panel de Control
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Acceso rápido a todas las secciones de gestión del sistema.
            </p>
        </div>

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

            {{-- Mesas --}}
            <a href="{{ route('tables.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-purple-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-purple-600">
                    Mesas
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Administración de las mesas disponibles.
                </p>
            </a>

            {{-- Reviews --}}
            <a href="{{ route('review.index') }}"
               class="group rounded-xl border border-gray-200 bg-gray-50 p-6 transition hover:border-yellow-500 hover:bg-white hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-900">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-yellow-600">
                    Reseñas
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Gestión de opiniones de platos y bebidas.
                </p>
            </a>
        </div>
    </div>
</x-layouts.app>

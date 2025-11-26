<x-layouts.app :title="__('Inicio')">
    <div class="py-8">
        <div class="mx-auto max-w-4xl">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-neutral-800">
                <h1 class="text-3xl font-bold">Bienvenido al TFG</h1>
                <p class="mt-2 text-sm text-gray-600">Panel de ejemplo con accesos rápidos a las secciones principales.</p>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <a href="{{ route('categories.index') }}" class="block rounded-lg border border-neutral-200 px-4 py-5 hover:bg-neutral-50">
                        <h3 class="text-lg font-medium">Categorías</h3>
                        <p class="mt-1 text-sm text-gray-500">Gestiona las categorías de platos y bebidas.</p>
                        <div class="mt-3">
                            <button class="rounded bg-indigo-600 px-3 py-1 text-white">Ir a Categorías</button>
                        </div>
                    </a>

                    <a href="{{ route('dishes.index') }}" class="block rounded-lg border border-neutral-200 px-4 py-5 hover:bg-neutral-50">
                        <h3 class="text-lg font-medium">Platos</h3>
                        <p class="mt-1 text-sm text-gray-500">Crear, editar y revisar los platos del menú.</p>
                        <div class="mt-3">
                            <button class="rounded bg-green-600 px-3 py-1 text-white">Ir a Platos</button>
                        </div>
                    </a>

                    <a href="{{ route('allergens.index') }}" class="block rounded-lg border border-neutral-200 px-4 py-5 hover:bg-neutral-50">
                        <h3 class="text-lg font-medium">Alérgenos</h3>
                        <p class="mt-1 text-sm text-gray-500">Gestiona los alérgenos de los platos y bebidas.</p>
                        <div class="mt-3">
                            <button class="rounded bg-red-600 px-3 py-1 text-white">Ir a Alérgenos</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

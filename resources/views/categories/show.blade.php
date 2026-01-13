<x-layouts.app :title="__('Detalle de Categoría')">
    <div class="max-w-4xl">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100 mb-6">{{ $category->name }}</h1>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <div class="space-y-4">
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Nombre:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $category->name }}</p>
                </div>
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Descripción:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $category->description ?? 'Sin descripción' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Categoría padre:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $category->parent?->name ?? '—' }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('categories.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Volver</a>
            </div>
        </div>
    </div>
</x-layouts.app>
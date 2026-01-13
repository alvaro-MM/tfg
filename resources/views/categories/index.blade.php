<x-layouts.app :title="__('Categorías')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Categorías</h1>
        <a href="{{ route('categories.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Crear nueva categoría</a>
    </div>

    <div class="mt-4">
        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('success') }}</div>
        @endif

        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Categoría Padre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $category->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $category->parent?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('categories.show', $category) }}" class="text-blue-600 dark:text-blue-400">Ver</a>
                            <a href="{{ route('categories.edit', $category) }}" class="ml-2 text-indigo-600 dark:text-indigo-400">Editar</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar categoría?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>

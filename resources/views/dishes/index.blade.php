<x-layouts.app :title="__('Platos')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Platos</h1>
        <a href="{{ route('dishes.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Nuevo plato</a>
    </div>

    <div class="mt-4">
        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('success') }}</div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Disponible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Alérgeno</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
                @foreach($dishes as $dish)
                <tr>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $dish->name }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $dish->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ number_format($dish->price, 2) }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $dish->available ? 'Sí' : 'No' }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        @if($dish->allergens->isEmpty())
                            -
                        @else
                            {{ $dish->allergens->pluck('name')->join(', ') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('dishes.show', $dish) }}" class="text-blue-600 dark:text-blue-400">Ver</a>
                        <a href="{{ route('dishes.edit', $dish) }}" class="ml-2 text-indigo-600 dark:text-indigo-400">Editar</a>
                        <form action="{{ route('dishes.destroy', $dish) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar este plato?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $dishes->links() }}
        </div>
    </div>
</x-layouts.app>

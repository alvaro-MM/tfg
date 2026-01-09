<x-layouts.admin title="Bebidas">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            Bebidas
        </h1>
        <a href="{{ route('drinks.create') }}"
            class="rounded bg-blue-600 px-4 py-2 text-white">
            Nueva bebida
        </a>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Disponible</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y dark:divide-zinc-700">
                @foreach($drinks as $drink)
                <tr>
                    <td class="px-6 py-4">{{ $drink->name }}</td>
                    <td class="px-6 py-4">{{ $drink->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ number_format($drink->price, 2) }}</td>
                    <td class="px-6 py-4">{{ $drink->available ? 'Sí' : 'No' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('drinks.show', $drink) }}" class="text-blue-600">Ver</a>
                        <a href="{{ route('drinks.edit', $drink) }}" class="ml-2 text-indigo-600">Editar</a>
                        <form action="{{ route('drinks.destroy', $drink) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar esta bebida?');">
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
            {{ $drinks->links() }}
        </div>
    </div>

</x-layouts.admin>
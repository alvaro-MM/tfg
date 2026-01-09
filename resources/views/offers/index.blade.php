<x-layouts.admin :title="__('Ofertas')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Ofertas</h1>
        @can('create', App\Models\Offer::class)
            <a href="{{ route('offers.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Nueva oferta</a>
        @endcan
    </div>

    <div class="mt-4">
        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800 dark:bg-red-900 dark:text-red-200">{{ session('error') }}</div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-zinc-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Descuento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Menú</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
            @foreach($offers as $offer)
                <tr>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $offer->name }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $offer->slug }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            {{ $offer->discount }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        @if($offer->menu)
                            {{ $offer->menu->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @can('view', $offer)
                            <a href="{{ route('offers.show', $offer) }}" class="text-blue-600 dark:text-blue-400">Ver</a>
                        @endcan
                        @can('update', $offer)
                            <a href="{{ route('offers.edit', $offer) }}" class="ml-2 text-indigo-600 dark:text-indigo-400">Editar</a>
                        @endcan
                        @can('delete', $offer)
                            <form action="{{ route('offers.destroy', $offer) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar esta oferta?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400">Eliminar</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $offers->links() }}
        </div>
    </div>
</x-layouts.admin>

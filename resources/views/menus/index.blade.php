<<<<<<< HEAD
<x-layouts.admin :title="__('Menús')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Menús</h1>
        @can('create', App\Models\Menu::class)
            <a href="{{ route('menus.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Nuevo menú</a>
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
=======
<x-layouts.app :title="__('Menús')">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Menús</h1>
        <a href="{{ route('menus.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Nuevo menú
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
>>>>>>> qr-menu-orders
            <thead class="bg-gray-50 dark:bg-zinc-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Oferta</th>
<<<<<<< HEAD
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Platos</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
            @foreach($menus as $menu)
                <tr>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $menu->name }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        <span class="px-2 py-1 text-xs rounded
                            @if($menu->type === 'daily') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($menu->type === 'special') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($menu->type === 'seasonal') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @endif">
                            {{ $menu->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        {{ number_format($menu->total_price, 2) }}
                        @if($menu->hasActiveOffer())
                            <span class="text-sm text-red-600 dark:text-red-400 line-through">{{ number_format($menu->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        @if($menu->offer)
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                -{{ $menu->offer->discount }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $menu->dishes->count() }} platos</td>
                    <td class="px-6 py-4 text-right">
                        @can('view', $menu)
                            <a href="{{ route('menus.show', $menu) }}" class="text-blue-600 dark:text-blue-400">Ver</a>
                        @endcan
                        @can('update', $menu)
                            <a href="{{ route('menus.edit', $menu) }}" class="ml-2 text-indigo-600 dark:text-indigo-400">Editar</a>
                        @endcan
                        @can('delete', $menu)
                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar este menú?');">
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
            {{ $menus->links() }}
        </div>
    </div>
</x-layouts.admin>
=======
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
            @forelse($menus as $menu)
                <tr>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $menu->name }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        {{ $menu->type === 'buffet' ? 'Buffet' : 'A la carta' }}
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        {{ $menu->price !== null ? number_format($menu->price, 2) . ' €' : '—' }}
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        {{ $menu->offer?->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('menus.edit', $menu) }}"
                           class="text-indigo-600 dark:text-indigo-400">Editar</a>

                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('¿Eliminar este menú?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No hay menús registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $menus->links() }}
    </div>

</x-layouts.app>


>>>>>>> qr-menu-orders

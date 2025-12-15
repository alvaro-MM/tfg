<x-layouts.app :title="__('Detalles del menú')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">{{ $menu->name }}</h1>
        <div class="flex space-x-2">
            @can('update', $menu)
                <a href="{{ route('menus.edit', $menu) }}" class="rounded bg-indigo-600 px-4 py-2 text-white">Editar</a>
            @endcan
            <a href="{{ route('menus.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Nombre</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ $menu->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Tipo</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        <span class="px-2 py-1 text-xs rounded
                            @if($menu->type === 'daily') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($menu->type === 'special') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($menu->type === 'seasonal') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @endif">
                            {{ $menu->type_label }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Precio base</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ number_format($menu->price, 2) }}€</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Precio total</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        {{ number_format($menu->total_price, 2) }}€
                        @if($menu->hasActiveOffer())
                            <span class="text-sm text-red-600 dark:text-red-400">(con oferta aplicada)</span>
                        @endif
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Oferta</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        @if($menu->offer)
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                {{ $menu->offer->name }} (-{{ $menu->offer->discount }}%)
                            </span>
                        @else
                            Sin oferta aplicada
                        @endif
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Platos incluidos</dt>
                    <dd class="mt-2">
                        @if($menu->dishes->count() > 0)
                            <div class="space-y-2">
                                @foreach($menu->dishes as $dish)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-zinc-700 rounded">
                                        <div>
                                            <span class="font-medium text-stone-900 dark:text-stone-100">{{ $dish->name }}</span>
                                            @if($dish->description)
                                                <p class="text-sm text-stone-600 dark:text-stone-400">{{ $dish->description }}</p>
                                            @endif
                                            @if($dish->category)
                                                <span class="text-xs text-stone-500 dark:text-stone-400">{{ $dish->category->name }}</span>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-stone-900 dark:text-stone-100">{{ number_format($dish->price, 2) }}€</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-stone-600 dark:text-stone-400">No hay platos asociados a este menú.</p>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @can('delete', $menu)
        <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-stone-900 dark:text-stone-100">Eliminar menú</h3>
                <div class="mt-2 max-w-xl text-sm text-stone-500 dark:text-stone-400">
                    <p>Esta acción no se puede deshacer. Eliminará permanentemente el menú y todos sus datos asociados.</p>
                </div>
                <div class="mt-5">
                    <form action="{{ route('menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este menú?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Eliminar menú</button>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</x-layouts.app>
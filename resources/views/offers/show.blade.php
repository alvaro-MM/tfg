<x-layouts.app :title="__('Detalles de la oferta')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">{{ $offer->name }}</h1>
        <div class="flex space-x-2">
            @can('update', $offer)
                <a href="{{ route('offers.edit', $offer) }}" class="rounded bg-indigo-600 px-4 py-2 text-white">Editar</a>
            @endcan
            <a href="{{ route('offers.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Nombre</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ $offer->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Slug</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ $offer->slug }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Descuento</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            {{ $offer->discount }}%
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Menú asociado</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        @if($offer->menu)
                            <a href="{{ route('menus.show', $offer->menu) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $offer->menu->name }}
                            </a>
                            <p class="text-xs text-stone-600 dark:text-stone-400 mt-1">
                                Precio base: {{ number_format($offer->menu->price, 2) }}€<br>
                                Precio con descuento: {{ number_format($offer->menu->total_price, 2) }}€
                            </p>
                        @else
                            No hay menú asociado
                        @endif
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Descripción</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ $offer->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @can('delete', $offer)
        <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-stone-900 dark:text-stone-100">Eliminar oferta</h3>
                <div class="mt-2 max-w-xl text-sm text-stone-500 dark:text-stone-400">
                    <p>Esta acción no se puede deshacer. Eliminará permanentemente la oferta.</p>
                </div>
                <div class="mt-5">
                    <form action="{{ route('offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta oferta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Eliminar oferta</button>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</x-layouts.app>

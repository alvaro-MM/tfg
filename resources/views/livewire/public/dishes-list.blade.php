<div>

    {{-- 🔎 Buscador + Categorías --}}
    <div class="mb-12 flex flex-col md:flex-row justify-center gap-4">

        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar plato..."
            class="w-full md:w-1/3 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-neutral-800 dark:text-white"
        >

        <select
            wire:model.live="category"
            class="w-full md:w-1/4 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-neutral-800 dark:text-white"
        >
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select
            wire:model.live="availability"
            class="w-full md:w-1/4 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-neutral-800 dark:text-white"
        >
            <option value="available">Solo disponibles</option>
            <option value="unavailable">No disponibles</option>
            <option value="all">Todos</option>
        </select>

    </div>

    {{-- Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($dishes as $dish)
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg overflow-hidden
                    hover:shadow-2xl transition transform hover:scale-[1.02] {{ !$dish->available ? 'opacity-70' : '' }}">
                {{-- Imagen --}}
                @if($dish->image)
                    <div class="h-52 overflow-hidden">
                        <img
                            src="{{ asset('storage/' . $dish->image) }}"
                            alt="{{ $dish->name }}"
                            class="w-full h-full object-cover hover:scale-110 transition duration-500"
                        >
                    </div>
                @else
                    <div class="h-52 bg-gray-200 dark:bg-neutral-700 flex items-center justify-center text-gray-400">
                        Sin imagen
                    </div>
                @endif

                <div class="p-6 space-y-3">

                    {{-- Nombre --}}
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $dish->name }}
                    </h3>

                    {{-- Categoría --}}
                    <p class="text-sm text-gray-500">
                        {{ $dish->category->name ?? 'Sin categoría' }}
                    </p>

                    {{-- Precio --}}
                    <div class="text-lg font-semibold text-red-600">
                        € {{ number_format($dish->price, 2) }}
                    </div>

                    {{-- Rating --}}
                    <div class="flex items-center gap-2 text-sm text-yellow-500">
                        ⭐
                        <span>
                            {{ $dish->reviews_avg_rating
                                ? number_format($dish->reviews_avg_rating, 1)
                                : 'Sin valoraciones' }}
                        </span>

                        @if($dish->reviews_count)
                            <span class="text-gray-400">
                                ({{ $dish->reviews_count }})
                            </span>
                        @endif
                    </div>

                    {{-- Descripción corta --}}
                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                        {{ $dish->description }}
                    </p>

                    {{-- Alérgenos --}}
                    @if($dish->allergens->count())
                        <div class="text-xs text-red-600">
                            ⚠ {{ $dish->allergens->pluck('name')->join(', ') }}
                        </div>
                    @endif

                    {{-- Botones --}}
                    <div class="flex gap-3 pt-4">

                        <a href="{{ route('dishes.show', $dish) }}"
                           class="flex-1 text-center bg-gray-200 dark:bg-neutral-700 hover:bg-gray-300 dark:hover:bg-neutral-600 rounded-lg py-2 text-sm transition">
                            Ver detalle
                        </a>

                        <a href="{{ route('review.create', ['dish_id' => $dish->id]) }}"
                           class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white rounded-lg py-2 text-sm transition">
                            Hacer reseña
                        </a>

                    </div>

                    @if(!$dish->available)
                        <span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                            No disponible
                        </span>
                    @endif

                </div>

            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">
                No se encontraron platos.
            </p>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-12 flex justify-center">
        {{ $dishes->links() }}
    </div>

</div>

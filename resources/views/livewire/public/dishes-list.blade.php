<div>

    {{-- 🔎 Buscador + Categorías --}}
    <div class="mb-12 flex flex-col md:flex-row justify-center gap-4">

        {{-- Buscador --}}
        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar plato..."
            class="w-full md:w-1/3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-neutral-800 dark:text-white"
        >

        {{-- Categorías --}}
        <select
            wire:model.live="category"
            class="w-full md:w-1/4 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-neutral-800 dark:text-white"
        >
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

    </div>

    {{-- Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($dishes as $dish)
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition border-l-4 border-red-500 transform hover:scale-105">

                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $dish->name }}
                </h3>

                <p class="text-sm text-gray-500 mt-1 mb-2">
                    {{ $dish->category->name ?? 'Sin categoría' }}
                </p>

                <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                    {{ $dish->description }}
                </p>

                @if($dish->allergens->count())
                    <div class="mt-4 text-xs text-red-600 font-medium">
                        ⚠️ Alérgenos: {{ $dish->allergens->pluck('name')->join(', ') }}
                    </div>
                @endif
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

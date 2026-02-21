<x-layouts.admin :title="__('Detalle plato')">

    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">
                {{ $dish->name }}
            </h1>

            <div class="flex gap-3">
                <a href="{{ route('dishes.edit', $dish) }}"
                   class="rounded-lg bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-white shadow transition">
                    Editar
                </a>

                <a href="{{ route('dishes.index') }}"
                   class="rounded-lg border border-gray-300 dark:border-zinc-600 px-4 py-2 text-sm
                          text-gray-700 dark:text-stone-200 hover:bg-gray-100 dark:hover:bg-zinc-700 transition">
                    Volver
                </a>
            </div>
        </div>

        {{-- Card principal --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg overflow-hidden">

            {{-- Imagen --}}
            @if($dish->image)
                <div class="relative">
                    <img
                        src="{{ asset('storage/' . $dish->image) }}"
                        alt="{{ $dish->name }}"
                        class="w-full h-80 object-cover"
                    >

                    {{-- Badge especial --}}
                    @if($dish->special)
                        <span class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm shadow">
                            ⭐ Especial
                        </span>
                    @endif
                </div>
            @endif

            <div class="p-6 space-y-6">

                {{-- Precio + Estados --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="text-2xl font-semibold text-green-600">
                        € {{ number_format($dish->price, 2) }}
                    </div>

                    <div class="flex gap-3">
                        <span class="px-3 py-1 rounded-full text-sm
                            {{ $dish->available
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $dish->available ? 'Disponible' : 'No disponible' }}
                        </span>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <h2 class="font-semibold text-lg mb-2">Descripción</h2>
                    <p class="text-gray-700 dark:text-stone-300 leading-relaxed">
                        {!! nl2br(e($dish->description)) !!}
                    </p>
                </div>

                {{-- Ingredientes --}}
                <div>
                    <h2 class="font-semibold text-lg mb-2">Ingredientes</h2>
                    <p class="text-gray-700 dark:text-stone-300">
                        {!! $dish->ingredients
                            ? nl2br(e($dish->ingredients))
                            : '-' !!}
                    </p>
                </div>

                {{-- Categoría --}}
                <div>
                    <h2 class="font-semibold text-lg mb-2">Categoría</h2>
                    <span class="inline-block bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm">
                        {{ $dish->category->name ?? '-' }}
                    </span>
                </div>

                {{-- Alérgenos --}}
                <div>
                    <h2 class="font-semibold text-lg mb-3">Alérgenos</h2>

                    @if($dish->allergens->isEmpty())
                        <p class="text-gray-500 dark:text-stone-400">Sin alérgenos registrados.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($dish->allergens as $allergen)
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm shadow-sm">
                                    {{ $allergen->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

</x-layouts.admin>

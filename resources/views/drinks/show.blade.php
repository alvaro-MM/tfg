<x-layouts.admin title="Detalle bebida">

    <div class="max-w-5xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">
                    {{ $drink->name }}
                </h1>

                {{-- Disponibilidad --}}
                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full
                    {{ $drink->available
                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                        : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                    {{ $drink->available ? 'Disponible' : 'No disponible' }}
                </span>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('drinks.edit', $drink) }}"
                   class="rounded-xl bg-indigo-600 hover:bg-indigo-700 transition px-4 py-2 text-white text-sm shadow">
                    Editar
                </a>

                <a href="{{ route('drinks.index') }}"
                   class="rounded-xl border px-4 py-2 text-sm text-stone-700 dark:text-stone-300 hover:bg-stone-100 dark:hover:bg-neutral-800 transition">
                    Volver
                </a>
            </div>
        </div>

        {{-- Card principal --}}
        <div class="bg-white dark:bg-neutral-900 shadow-xl rounded-2xl overflow-hidden">

            {{-- Imagen --}}
            @if($drink->image)
                <div class="h-64 w-full overflow-hidden">
                    <img
                        src="{{ asset('storage/'.$drink->image) }}"
                        class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-8 space-y-6">

                {{-- Precio --}}
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Precio
                    </p>
                    <p class="text-2xl font-bold text-green-600">
                        € {{ number_format($drink->price, 2) }}
                    </p>
                </div>

                {{-- Descripción --}}
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Descripción
                    </p>
                    <p class="mt-1 text-stone-800 dark:text-stone-200 leading-relaxed">
                        {{ $drink->description }}
                    </p>
                </div>

                {{-- Categoría --}}
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Categoría
                    </p>
                    <p class="mt-1 font-medium">
                        {{ $drink->category->name ?? '-' }}
                    </p>
                </div>

                {{-- Alérgenos --}}
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Alérgenos
                    </p>

                    @if($drink->allergens->isEmpty())
                        <p class="text-gray-400 italic">Sin alérgenos registrados</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($drink->allergens as $a)
                                <span class="px-3 py-1 text-xs rounded-full
                                    bg-amber-100 text-amber-700
                                    dark:bg-amber-900 dark:text-amber-300">
                                    {{ $a->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

</x-layouts.admin>

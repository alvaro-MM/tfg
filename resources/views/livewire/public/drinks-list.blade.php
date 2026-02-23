<div>
    <div class="flex flex-wrap gap-4 mb-8">

        {{-- Buscador --}}
        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar bebida..."
            class="px-4 py-3 rounded-xl border"
        >

        {{-- Categoría --}}
        <select wire:model.live="category" class="px-4 py-3 rounded-xl border">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- Disponibilidad --}}
        <select wire:model.live="availability" class="px-4 py-3 rounded-xl border">
            <option value="available">Solo disponibles</option>
            <option value="unavailable">No disponibles</option>
            <option value="all">Todas</option>
        </select>

    </div>

    <div class="grid md:grid-cols-3 gap-8">
        @foreach($drinks as $drink)
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg overflow-hidden
                        {{ !$drink->available ? 'opacity-70' : '' }}">

                {{-- Imagen --}}
                @if($drink->image)
                    <img src="{{ asset('storage/'.$drink->image) }}"
                         class="w-full h-48 object-cover">
                @endif

                <div class="p-6">

                    <h2 class="text-xl font-bold">
                        {{ $drink->name }}
                    </h2>

                    {{-- No disponible --}}
                    @if(!$drink->available)
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">
                            No disponible
                        </span>
                    @endif

                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        {{ $drink->description }}
                    </p>

                    <p class="mt-4 font-semibold text-green-600">
                        € {{ $drink->price }}
                    </p>

                    {{-- Rating --}}
                    <div class="mt-2 text-yellow-400">
                        ★ {{ number_format($drink->reviews_avg_rating, 1) ?? '0.0' }}
                        ({{ $drink->reviews_count }} reseñas)
                    </div>

                    {{-- Botón reseña --}}
                    <a href="{{ route('review.create', ['drink_id' => $drink->id]) }}"
                       class="block mt-4 text-center bg-green-600 text-white py-2 rounded-lg">
                        Hacer reseña
                    </a>

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $drinks->links() }}
    </div>
</div>

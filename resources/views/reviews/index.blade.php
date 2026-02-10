<x-layouts.admin :title="__('Reviews')">
    <div class="max-w-5xl mx-auto py-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-stone-100">
                    Listado de Reviews
                </h1>
                <p class="text-sm text-gray-500 dark:text-stone-400">
                    Gestión de reseñas realizadas por los usuarios
                </p>
            </div>

            <a href="{{ route('review.create') }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                + Nueva Review
            </a>
        </div>

        {{-- Listado --}}
        <div class="grid gap-4">
            @forelse ($reviews as $review)
                <div
                    class="p-5 rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-gray-200 dark:border-zinc-700">

                    {{-- Título + rating --}}
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-stone-100">
                                {{ $review->name }}
                            </h2>

                            {{-- Producto reseñado --}}
                            <p class="text-sm text-gray-500 dark:text-stone-400 mt-1">
                                @if($review->dish)
                                    🍽️ Plato: <span class="font-medium">{{ $review->dish->name }}</span>
                                @elseif($review->drink)
                                    🥤 Bebida: <span class="font-medium">{{ $review->drink->name }}</span>
                                @else
                                    Producto no especificado
                                @endif
                            </p>
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center gap-1 text-yellow-400">
                            @if($review->rating)
                                @for($i = 0; $i < $review->rating; $i++)
                                    ⭐
                                @endfor
                                <span class="ml-1 text-sm text-gray-500 dark:text-stone-400">
                                    ({{ $review->rating }}/5)
                                </span>
                            @else
                                <span class="text-sm text-gray-400">Sin valorar</span>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <p class="mt-3 text-gray-700 dark:text-stone-200">
                        {{ Str::limit($review->description, 180) }}
                    </p>

                    {{-- Footer --}}
                    <div class="mt-4 flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-stone-400">
                            Por <span class="font-medium">{{ $review->user->name }}</span> ·
                            {{ $review->created_at->diffForHumans() }}
                        </span>

                        <div class="flex gap-3">
                            <a href="{{ route('review.show', $review) }}"
                               class="text-blue-600 hover:underline">
                                Ver
                            </a>

                            <a href="{{ route('review.edit', $review) }}"
                               class="text-yellow-600 hover:underline">
                                Editar
                            </a>

                            <form action="{{ route('review.destroy', $review) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar esta review?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-12 text-gray-500 dark:text-stone-400">
                    No hay reviews todavía.
                </div>
            @endforelse
        </div>

         <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</x-layouts.admin>

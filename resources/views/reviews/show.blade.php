<x-layouts.admin :title="__('Detalle de Review')">
    <div class="max-w-4xl">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100 mb-6">{{ $review->name }}</h1>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <div class="space-y-4">
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Nombre:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $review->name }}</p>
                </div>
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Contenido:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $review->content ?? 'Sin descripción' }}</p>
                </div>
                @if($review->user)
                <div>
                    <strong class="text-gray-700 dark:text-gray-300">Usuario:</strong>
                    <p class="text-stone-900 dark:text-stone-100">{{ $review->user->name }}</p>
                </div>
                @endif

                @if($review->rating)
                    <div class="flex items-center gap-1 text-yellow-400 mt-2">
                        @for($i = 0; $i < $review->rating; $i++)
                            ⭐
                        @endfor
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                            ({{ $review->rating }}/5)
                        </span>
                    </div>
                @endif

            </div>

            <div class="mt-6">
                <a href="{{ route('review.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Volver</a>
            </div>
        </div>
    </div>
</x-layouts.admin>

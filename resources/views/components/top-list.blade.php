@props([
    'title',
    'items'
])

<div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow">

    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
        {{ $title }}
    </h2>

    <div class="space-y-3">
        @forelse($items as $item)
            <div class="flex justify-between items-center border-b pb-2 last:border-none">

                <span class="font-medium text-gray-700 dark:text-stone-200">
                    {{ $item->name }}
                </span>

                <span class="text-yellow-500 font-semibold">
                    ⭐ {{ number_format($item->reviews_avg_rating ?? 0, 1) }}
                </span>

            </div>
        @empty
            <p class="text-sm text-gray-500">
                Sin datos disponibles
            </p>
        @endforelse
    </div>

</div>

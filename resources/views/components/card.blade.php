@props([
    'title',
    'value',
    'icon' => null
])

<div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow hover:shadow-lg transition">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500 dark:text-stone-400">
                {{ $title }}
            </p>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                {{ $value }}
            </p>
        </div>

        @if($icon)
            <div class="text-4xl">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>

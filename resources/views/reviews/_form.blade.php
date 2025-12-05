<div class="space-y-6">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Nombre de la Reseña
        </label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $review->name ?? '') }}"
            required
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-stone-100 shadow focus:ring-2 focus:ring-green-600 focus:border-transparent"
        />
        @error('name')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Descripción
        </label>
        <textarea
            name="description"
            rows="4"
            required
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-stone-100 shadow focus:ring-2 focus:ring-green-600 focus:border-transparent"
        >{{ old('description', $review->description ?? '') }}</textarea>
        @error('description')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Plato --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Seleccionar Plato
        </label>
        <select
            name="dish_id"
            required
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-stone-100 shadow focus:ring-2 focus:ring-green-600 focus:border-transparent"
        >
            <option value="">-- Selecciona un plato --</option>

            @foreach ($dishes as $dish)
                <option
                    value="{{ $dish->id }}"
                    {{ old('dish_id', $review->dish_id ?? '') == $dish->id ? 'selected' : '' }}
                >
                    🍽️ {{ $dish->name }}
                </option>
            @endforeach
        </select>
        @error('dish_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bebida --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Seleccionar Bebida
        </label>
        <select
            name="drink_id"
            required
            class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-stone-100 shadow focus:ring-2 focus:ring-green-600 focus:border-transparent"
        >
            <option value="">-- Selecciona una bebida --</option>

            @foreach ($drinks as $drink)
                <option
                    value="{{ $drink->id }}"
                    {{ old('drink_id', $review->drink_id ?? '') == $drink->id ? 'selected' : '' }}
                >
                    🥤 {{ $drink->name }}
                </option>
            @endforeach
        </select>
        @error('drink_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

</div>

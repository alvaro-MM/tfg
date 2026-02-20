<form wire:submit.prevent="save" class="space-y-6 w-full p-6">

    <h1 class="text-3xl font-bold text-green-600">
        {{ $review ? 'Editar Reseña' : 'Crear Reseña' }}
    </h1>

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Nombre de la Reseña
        </label>

        <input
            type="text"
            wire:model="name"
            class="mt-1 w-full rounded-lg border-2
                @error('name') border-red-500 focus:ring-red-500
                @else border-gray-400 dark:border-zinc-600 focus:border-green-600 focus:ring-green-600
                @enderror
                bg-white dark:bg-zinc-800
                text-gray-800 dark:text-stone-100
                shadow-sm focus:ring-2 focus:outline-none
                transition"
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
            wire:model="description"
            rows="4"
            class="mt-1 w-full rounded-lg border-2
                @error('description') border-red-500 focus:ring-red-500
                @else border-gray-400 dark:border-zinc-600 focus:border-green-600 focus:ring-green-600
                @enderror
                bg-white dark:bg-zinc-800
                text-gray-800 dark:text-stone-100
                shadow-sm focus:ring-2 focus:outline-none
                transition"
        ></textarea>

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
            wire:model="dish_id"
            class="mt-1 w-full rounded-lg border-2
                @error('dish_id') border-red-500 focus:ring-red-500
                @else border-gray-400 dark:border-zinc-600 focus:border-green-600 focus:ring-green-600
                @enderror
                bg-white dark:bg-zinc-800
                text-gray-800 dark:text-stone-100
                shadow-sm focus:ring-2 focus:outline-none
                transition"
        >
            <option value="">-- Selecciona un plato --</option>
            @foreach($dishes as $dish)
                <option value="{{ $dish->id }}">🍽️ {{ $dish->name }}</option>
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
            wire:model="drink_id"
            class="mt-1 w-full rounded-lg border-2
                @error('drink_id') border-red-500 focus:ring-red-500
                @else border-gray-400 dark:border-zinc-600 focus:border-green-600 focus:ring-green-600
                @enderror
                bg-white dark:bg-zinc-800
                text-gray-800 dark:text-stone-100
                shadow-sm focus:ring-2 focus:outline-none
                transition"
        >
            <option value="">-- Selecciona una bebida --</option>
            @foreach($drinks as $drink)
                <option value="{{ $drink->id }}">🥤 {{ $drink->name }}</option>
            @endforeach
        </select>

        @error('drink_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Rating --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-stone-200">
            Valoración
        </label>

        <div class="flex gap-2 mt-2">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" wire:click="$set('rating', {{ $i }})">
                    <span class="text-3xl cursor-pointer
                        transition
                        {{ $i <= $rating ? 'text-yellow-400 scale-110' : 'text-gray-300' }}">
                        ★
                    </span>
                </button>
            @endfor
        </div>

        @error('rating')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Imagen --}}
    <div>
        <x-drag-and-drop
            wire:model="image"
            :image="$image"
            :image-preview="$imagePreview"
            label="Arrastra tu imagen aquí o haz click"
            button-label="Seleccionar Imagen"
        />
        @error('image')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Botón --}}
    <button
        type="submit"
        class="w-full py-4 bg-green-600 hover:bg-green-700
               text-white font-semibold rounded-lg
               transition shadow-lg text-lg"
    >
        {{ $review ? 'Actualizar Review' : 'Crear Review' }}
    </button>

</form>

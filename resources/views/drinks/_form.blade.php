<div class="space-y-5">

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Nombre
        </label>
        <input
            name="name"
            value="{{ old('name', $drink->name ?? '') }}"
            required
            class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100" />
        @error('name')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Descripción
        </label>
        <textarea
            name="description"
            rows="4"
            required
            class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100">{{ old('description', $drink->description ?? '') }}</textarea>
        @error('description')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Imagen
        </label>

        @if(!empty($drink->image))
        <div class="mb-2">
            <img
                src="{{ asset('storage/'.$drink->image) }}"
                class="h-24 rounded-md border dark:border-zinc-700" />
        </div>
        @endif

        <input
            type="file"
            name="image"
            accept="image/*"
            class="mt-1 block w-full text-sm text-gray-700
                   dark:text-stone-200" />
        @error('image')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Precio
        </label>
        <input
            type="number"
            step="0.01"
            name="price"
            value="{{ old('price', $drink->price ?? '0.00') }}"
            required
            class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100" />
        @error('price')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <input type="hidden" name="available" value="0">
        <input
            type="checkbox"
            name="available"
            value="1"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600"
            {{ old('available', $drink->available ?? true) ? 'checked' : '' }} />
        <span class="text-sm text-gray-700 dark:text-stone-200">
            Disponible
        </span>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Categoría
        </label>
        <select
            name="category_id"
            class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100">
            @foreach($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ old('category_id', $drink->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @error('category_id')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">
            Alérgenos
        </label>
        <select
            multiple
            name="allergen_ids[]"
            class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm
                   dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100">
            @foreach($allergens as $allergen)
            <option
                value="{{ $allergen->id }}"
                {{ in_array($allergen->id, old('allergen_ids', isset($drink) ? $drink->allergens->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                {{ $allergen->name }}
            </option>
            @endforeach
        </select>
    </div>
</div>
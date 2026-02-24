<div class="space-y-6">

    <div>
        <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-200">
            Nombre
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $allergen->name ?? '') }}" required class="mt-1 block w-full rounded-lg border-stone-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-stone-700 dark:text-stone-200">
            Descripción
        </label>
        <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-lg border-stone-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100">{{ old('description', $allergen->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-stone-700 dark:text-stone-200">
            Imagen
        </label>

        <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-stone-100 file:px-4 file:py-2 file:text-stone-700 hover:file:bg-stone-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100 dark:file:bg-stone-700 dark:file:text-stone-200 dark:hover:file:bg-stone-600">

        <p class="mt-1 text-xs text-stone-500 dark:text-stone-400">
            JPG o PNG - recomendado 640x480 px
        </p>

        @if(!empty($allergen->image))
        <div class="mt-4 flex items-center gap-4">
            <img src="{{ asset($allergen->image) }}" alt="{{ $allergen->name }}" class="h-16 w-16 rounded-lg object-cover ring-2 ring-stone-200 dark:ring-stone-600 shadow">
            <span class="text-sm text-stone-600 dark:text-stone-400">
                Imagen actual
            </span>
        </div>
        @endif
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200 dark:border-stone-700">
        <a href="{{ route('allergens.index') }}" class="text-sm text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-200">
            Cancelar
        </a>

        <button type="submit" class="rounded-lg bg-green-600 px-5 py-2 text-sm font-medium text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition">
            {{ $buttonText }}
        </button>
    </div>

</div>
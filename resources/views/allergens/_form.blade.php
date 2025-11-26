<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-stone-200">Nombre</label>
    <input type="text" name="name" id="name" value="{{ old('name', $allergen->name ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100">
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-stone-200">Descripción</label>
    <textarea name="description" id="description" rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100">{{ old('description', $allergen->description ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-stone-200">Imagen</label>
    <input type="file" name="image" id="image"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-100">

    @if(!empty($allergen->image))
        <div class="mt-2">
            <p class="text-sm text-gray-500 dark:text-stone-400">Imagen actual:</p>
            <img src="{{ $allergen->image }}" alt="{{ $allergen->name }}" class="h-20 rounded mt-1">
        </div>
    @endif
</div>

<div class="flex items-center space-x-2">
    <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">{{ $buttonText }}</button>
    <a href="{{ route('allergens.index') }}" class="text-sm text-gray-600 dark:text-stone-300">Cancelar</a>
</div>

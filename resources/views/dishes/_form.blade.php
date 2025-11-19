<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Nombre</label>
        <input name="name" value="{{ old('name', $dish->name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm" />
        @error('name')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Descripción</label>
        <textarea name="description" required class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">{{ old('description', $dish->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Ingredientes</label>
        <textarea name="ingredients" class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">{{ old('ingredients', $dish->ingredients ?? '') }}</textarea>
        @error('ingredients')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Imagen (subir)</label>
        @if(!empty($dish->image))
            <div class="mb-2">
                <img src="{{ asset('storage/'.$dish->image) }}" alt="Imagen plato" class="h-24 rounded" />
            </div>
        @endif
        <input name="image" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm" />
        @error('image')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Precio</label>
        <input name="price" type="number" step="0.01" value="{{ old('price', isset($dish) ? $dish->price : '0.00') }}" required class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm" />
        @error('price')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-4">
        <input type="hidden" name="available" value="0" />
        <label class="inline-flex items-center">
            <input type="checkbox" name="available" value="1" {{ old('available', $dish->available ?? true) ? 'checked' : '' }} class="form-checkbox" />
            <span class="ml-2 dark:text-stone-200">Disponible</span>
        </label>

        <input type="hidden" name="special" value="0" />
        <label class="inline-flex items-center">
            <input type="checkbox" name="special" value="1" {{ old('special', $dish->special ?? false) ? 'checked' : '' }} class="form-checkbox" />
            <span class="ml-2 dark:text-stone-200">Especial</span>
        </label>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Categoría</label>
        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">
            <option value="">-- Ninguna --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (old('category_id', $dish->category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Alérgenos</label>
        <input id="allergen-search" type="text" placeholder="Buscar alérgenos..." class="mt-1 mb-2 block w-full rounded-md border border-gray-300 px-2 py-1" />
        <select id="allergen-select" name="allergen_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">
            @foreach($allergens as $allergen)
                <option value="{{ $allergen->id }}" {{ in_array($allergen->id, old('allergen_ids', isset($dish) ? $dish->allergens->pluck('id')->toArray() : [])) ? 'selected' : '' }}>{{ $allergen->name }}</option>
            @endforeach
        </select>
        @error('allergen_ids')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

@push('scripts')
<script>
// Simple client-side filter for the multiple select: filters options by text
(function(){
    const search = document.getElementById('allergen-search');
    const select = document.getElementById('allergen-select');
    if (!search || !select) return;

    search.addEventListener('input', function(e){
        const q = e.target.value.trim().toLowerCase();
        for (const option of select.options) {
            const text = option.text.toLowerCase();
            option.hidden = q !== '' && !text.includes(q);
        }
    });

    // optional: focus select when pressing down on search
    search.addEventListener('keydown', function(e){
        if (e.key === 'ArrowDown') {
            select.focus();
            e.preventDefault();
        }
    });
})();
</script>
@endpush
</div>

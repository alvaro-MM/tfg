<form wire:submit.prevent="save" class="space-y-6 max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold text-green-600">
        {{ $dish ? 'Editar Plato' : 'Crear Plato' }}
    </h1>

    {{-- Nombre --}}
    <div>
        <label class="font-semibold">Nombre</label>
        <input type="text" wire:model="name"
               class="w-full mt-1 border-2 rounded-lg p-2 focus:border-green-600">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Categoría --}}
    <div>
        <label class="font-semibold">Categoría</label>
        <select wire:model="category_id"
                class="w-full mt-1 border-2 rounded-lg p-2 focus:border-green-600">
            <option value="">Seleccionar categoría</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Descripción --}}
    <div>
        <label class="font-semibold">Descripción</label>
        <textarea wire:model="description" rows="3"
                  class="w-full mt-1 border-2 rounded-lg p-2 focus:border-green-600"></textarea>
        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Ingredientes --}}
    <div>
        <label class="font-semibold">Ingredientes</label>
        <textarea wire:model="ingredients" rows="3"
                  class="w-full mt-1 border-2 rounded-lg p-2 focus:border-green-600"></textarea>
    </div>

    {{-- Precio --}}
    <div>
        <label class="font-semibold">Precio (€)</label>
        <input type="number" step="0.01" wire:model="price"
               class="w-full mt-1 border-2 rounded-lg p-2 focus:border-green-600">
        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Booleanos --}}
    <div class="flex gap-6">
        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="available">
            Disponible
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="special">
            Especial
        </label>
    </div>

    {{-- Alérgenos --}}
    <div>
        <label class="font-semibold block mb-2">Alérgenos</label>
        <div class="grid grid-cols-2 gap-2">
            @foreach($allAllergens as $allergen)
                <label class="flex items-center gap-2">
                    <input type="checkbox"
                           value="{{ $allergen->id }}"
                           wire:model="allergens">
                    {{ $allergen->name }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- Imagen --}}
    <div>
        <x-drag-and-drop
            wire:model="image"
            :image="$image"
            :image-preview="$imagePreview"
            label="Arrastra la imagen del plato"
            button-label="Seleccionar Imagen"
        />
    </div>

    <button type="submit"
            class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-lg transition">
        {{ $dish ? 'Actualizar Plato' : 'Crear Plato' }}
    </button>

</form>

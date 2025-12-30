<x-layouts.app :title="__('Editar Categoría')">
    <div class="max-w-4xl">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100 mb-6">Editar Categoría</h1>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                    <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name', $category->name) }}">
                    @error('name') <small class="text-red-600 dark:text-red-400">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                    <textarea name="description" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                    @error('description') <small class="text-red-600 dark:text-red-400">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría Padre</label>
                    <select name="parent_id" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Ninguna --</option>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id') <small class="text-red-600 dark:text-red-400">{{ $message }}</small> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">Actualizar</button>
                    <a href="{{ route('categories.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
<x-layouts.app :title="__('Editar oferta')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar oferta</h1>
        <a href="{{ route('offers.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
    </div>

    <div class="mt-4">
        @if($errors->any())
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800 dark:bg-red-900 dark:text-red-200">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('offers.update', $offer) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $offer->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $offer->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">Identificador único para URLs (ej: oferta-navidad)</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Descripción</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $offer->description) }}</textarea>
            </div>

            <div>
                <label for="discount" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Descuento (%)</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount', $offer->discount) }}" min="1" max="100" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="menu_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Menú</label>
                <select name="menu_id" id="menu_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Seleccionar menú</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" {{ old('menu_id', $offer->menu_id) == $menu->id ? 'selected' : '' }}>
                            {{ $menu->name }} - {{ number_format($menu->price, 2) }}€
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Actualizar oferta</button>
            </div>
        </form>
    </div>
</x-layouts.app>

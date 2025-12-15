<x-layouts.app :title="__('Editar menú')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar menú</h1>
        <a href="{{ route('menus.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
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

        <form action="{{ route('menus.update', $menu) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Tipo</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Seleccionar tipo</option>
                    <option value="daily" {{ old('type', $menu->type) === 'daily' ? 'selected' : '' }}>Diario</option>
                    <option value="special" {{ old('type', $menu->type) === 'special' ? 'selected' : '' }}>Especial</option>
                    <option value="seasonal" {{ old('type', $menu->type) === 'seasonal' ? 'selected' : '' }}>Estacional</option>
                    <option value="event" {{ old('type', $menu->type) === 'event' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Precio base (€)</label>
                <input type="number" name="price" id="price" value="{{ old('price', $menu->price) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="offer_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Oferta (opcional)</label>
                <select name="offer_id" id="offer_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Sin oferta</option>
                    @foreach($offers as $offer)
                        <option value="{{ $offer->id }}" {{ old('offer_id', $menu->offer_id) == $offer->id ? 'selected' : '' }}>
                            {{ $offer->name }} (-{{ $offer->discount }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">Platos</label>
                <div class="mt-2 space-y-2">
                    @foreach($dishes as $dish)
                        <label class="flex items-center">
                            <input type="checkbox" name="dishes[]" value="{{ $dish->id }}" {{ in_array($dish->id, old('dishes', $menu->dishes->pluck('id')->toArray())) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-stone-900 dark:text-stone-100">{{ $dish->name }} - {{ number_format($dish->price, 2) }}€</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Actualizar menú</button>
            </div>
        </form>
    </div>
</x-layouts.app>
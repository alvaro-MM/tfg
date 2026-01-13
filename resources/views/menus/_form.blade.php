<div class="space-y-6">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Nombre del menú</label>
        <input type="text" name="name"
               value="{{ old('name', $menu->name ?? '') }}"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
               required>
        @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tipo --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Tipo</label>
        <select name="type"
                class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
            @foreach(['buffet' => 'Buffet', 'a_la_carta' => 'A la carta'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $menu->type ?? 'buffet') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('type')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Precio --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Precio</label>
        <input type="number" step="0.01" min="0" name="price"
               value="{{ old('price', $menu->price ?? 0) }}"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('price')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Oferta --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Oferta asociada</label>
        <select name="offer_id"
                class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Sin oferta</option>
            @foreach($offers as $offer)
                <option value="{{ $offer->id }}" @selected(old('offer_id', $menu->offer_id ?? null) == $offer->id)>
                    {{ $offer->name }} ({{ $offer->discount }} %)
                </option>
            @endforeach
        </select>
        @error('offer_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Platos --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Platos incluidos</label>
        <select name="dish_ids[]" multiple
                class="mt-1 w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @php
                $selectedDishes = old('dish_ids', isset($menu) ? $menu->dishes->pluck('id')->toArray() : []);
            @endphp
            @foreach($dishes as $dish)
                <option value="{{ $dish->id }}" @selected(in_array($dish->id, $selectedDishes))>
                    {{ $dish->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">
            Mantén pulsada la tecla Ctrl (Cmd en Mac) para seleccionar varios platos.
        </p>
        @error('dish_ids')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>



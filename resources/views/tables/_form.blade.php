<div class="space-y-6">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Nombre de la mesa</label>
        <input type="text" name="name"
               placeholder="Ej: Mesa A1"
               value="{{ old('name', $table->name ?? '') }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
               required>
    </div>

    {{-- Capacidad --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Capacidad</label>
        <input type="number" name="capacity"
               min="1"
               value="{{ old('capacity', $table->capacity ?? 1) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
               required>
    </div>

    {{-- Estado --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Estado</label>
        <select name="status"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required>
            @foreach([
                'available' => 'Disponible',
                'occupied' => 'Ocupada',
                'reserved' => 'Reservada'
            ] as $key => $label)
                <option value="{{ $key }}"
                    @selected(old('status', $table->status ?? 'available') === $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Notas --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Notas</label>
        <textarea name="notes" rows="3"
                  placeholder="Observaciones, alergias, peticiones especiales…"
                  class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $table->notes ?? '') }}</textarea>
    </div>

    {{-- Usuario asignado --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Asignada a usuario</label>
        <select name="user_id"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Sin asignar</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}"
                    @selected(old('user_id', $table->user_id ?? null) == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Menú asignado --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700">Menú asignado</label>
        <select name="menu_id"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Sin menú</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}"
                    @selected(old('menu_id', $table->menu_id ?? null) == $menu->id)>
                    {{ $menu->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<x-layouts.app :title="__('Editar factura')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar factura</h1>
        <a href="{{ route('invoices.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
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

        <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="table_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Mesa</label>
                <select name="table_id" id="table_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Seleccionar mesa</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ old('table_id', $invoice->table_id) == $table->id ? 'selected' : '' }}>
                            Mesa {{ $table->number }} - Capacidad: {{ $table->capacity }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="total" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Total (€)</label>
                <input type="number" name="total" id="total" value="{{ old('total', $invoice->total) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Fecha</label>
                <input type="date" name="date" id="date" value="{{ old('date', $invoice->date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Actualizar factura</button>
            </div>
        </form>
    </div>
</x-layouts.app>
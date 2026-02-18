<x-layouts.admin :title="__('Editar factura')">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar factura</h1>
            <p class="text-sm text-stone-500 dark:text-stone-400">Modifica los datos de la factura</p>
        </div>

        <a href="{{ route('invoices.index') }}"
            class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600">
            Volver
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">

        @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800 dark:bg-red-950 dark:text-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="table_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    Mesa
                </label>
                <select name="table_id" id="table_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccionar mesa</option>
                    @foreach($tables as $table)
                    <option value="{{ $table->id }}" {{ old('table_id', $invoice->table_id) == $table->id ? 'selected' : '' }}>
                        Mesa {{ $table->number }} — Capacidad: {{ $table->capacity }} personas
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="total" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    Total (€)
                </label>
                <input type="number" name="total" id="total" step="0.01" min="0" required
                    value="{{ old('total', $invoice->total) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                    Fecha
                </label>
                <input type="date" name="date" id="date" required
                    value="{{ old('date', $invoice->date->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="rounded-md bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Actualizar factura
                </button>
            </div>

        </form>
    </div>

</x-layouts.admin>
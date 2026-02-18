<x-layouts.admin :title="__('Crear factura')">

    <div class="flex flex-col items-center justify-center min-h-[70vh]">

        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">Crear factura</h1>
            <p class="text-sm text-stone-500 dark:text-stone-400 mt-1">
                Ingresa los datos de la nueva factura
            </p>
        </div>

        <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-md dark:border-zinc-700 dark:bg-zinc-800">

            @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800 dark:bg-red-950 dark:text-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('invoices.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="table_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Mesa
                    </label>
                    <select name="table_id" id="table_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona una mesa</option>
                        @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                            Mesa {{ $table->number }} — Capacidad: {{ $table->capacity }} personas
                        </option>
                        @endforeach
                    </select>
                    @error('table_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>

                    <label for="order_id" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Pedido asociado
                    </label>

                    <select name="order_id" id="order_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona un pedido</option>
                        @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            Pedido #{{ $order->id }} — {{ $order->type }} — {{ $order->date->format('d/m/Y') }}
                        </option>
                        @endforeach
                    </select>

                    @error('order_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Total (€)
                    </label>
                    <input type="number" name="total" id="total" step="0.01" min="0" required
                        value="{{ old('total') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('total')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                        Fecha
                    </label>
                    <input type="date" name="date" id="date" required
                        value="{{ old('date', date('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-stone-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="rounded-md bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Crear factura
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('invoices.index') }}"
                    class="text-sm text-gray-600 hover:underline dark:text-gray-300">
                    Volver al listado
                </a>
            </div>

        </div>
    </div>

</x-layouts.admin>
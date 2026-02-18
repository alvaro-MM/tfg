<x-layouts.admin :title="__('Facturas')">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
                Facturas
            </h1>
            <p class="text-sm text-stone-500 dark:text-stone-400">
                Listado y gestión de facturas
            </p>
        </div>

        @can('create', App\Models\Invoice::class)
        <a href="{{ route('invoices.create') }}"
            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Nueva factura
        </a>
        @endcan
    </div>

    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800">
        <form method="GET" action="{{ route('invoices.index') }}"
            class="flex flex-wrap items-end gap-4">

            <div class="flex flex-col">
                <label for="price_range"
                    class="mb-1 text-sm font-medium text-stone-700 dark:text-stone-200">
                    Importe
                </label>

                <select name="price_range" id="price_range"
                    class="w-48 rounded-md border-gray-300 text-sm dark:border-zinc-600 dark:bg-zinc-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="0-50" {{ request('price_range') == '0-50' ? 'selected' : '' }}>0 - 50 €</option>
                    <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>50 - 100 €</option>
                    <option value="100-200" {{ request('price_range') == '100-200' ? 'selected' : '' }}>100 - 200 €</option>
                    <option value="200-500" {{ request('price_range') == '200-500' ? 'selected' : '' }}>200 - 500 €</option>
                    <option value="500+" {{ request('price_range') == '500+' ? 'selected' : '' }}>Más de 500 €</option>
                </select>
            </div>

            <button type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Aplicar filtros
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-lg bg-green-100 p-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-800 dark:bg-red-900 dark:text-red-200">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">Mesa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                    <td class="px-6 py-4 text-sm text-stone-900 dark:text-stone-100">
                        #{{ $invoice->id }}
                    </td>

                    <td class="px-6 py-4 text-sm text-stone-900 dark:text-stone-100">
                        {{ $invoice->table ? 'Mesa ' . $invoice->table->number : '-' }}
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-stone-900 dark:text-stone-100">
                        {{ number_format($invoice->total, 2) }} €
                    </td>

                    <td class="px-6 py-4 text-sm text-stone-900 dark:text-stone-100">
                        {{ $invoice->date->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        @can('view', $invoice)
                        <a href="{{ route('invoices.show', $invoice) }}"
                            class="font-medium text-blue-600 hover:underline dark:text-blue-400">
                            Ver
                        </a>
                        @endcan

                        @can('update', $invoice)
                        <a href="{{ route('invoices.edit', $invoice) }}"
                            class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                            Editar
                        </a>
                        @endcan

                        @can('delete', $invoice)
                        <form action="{{ route('invoices.destroy', $invoice) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('¿Eliminar esta factura?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="font-medium text-red-600 hover:underline dark:text-red-400">
                                Eliminar
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        No hay facturas para los filtros seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $invoices->links() }}
    </div>

</x-layouts.admin>
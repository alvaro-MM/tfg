<x-layouts.app :title="__('Facturas')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Facturas</h1>
        @can('create', App\Models\Invoice::class)
            <a href="{{ route('invoices.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Nueva factura</a>
        @endcan
    </div>

    <div class="mt-4">
        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800 dark:bg-red-900 dark:text-red-200">{{ session('error') }}</div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mesa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-zinc-800 dark:divide-zinc-700">
                @foreach($invoices as $invoice)
                <tr>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">#{{ $invoice->id }}</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">
                        @if($invoice->table)
                            Mesa {{ $invoice->table->number }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ number_format($invoice->total, 2) }}€</td>
                    <td class="px-6 py-4 text-stone-900 dark:text-stone-100">{{ $invoice->date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        @can('view', $invoice)
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 dark:text-blue-400">Ver</a>
                        @endcan
                        @can('update', $invoice)
                            <a href="{{ route('invoices.edit', $invoice) }}" class="ml-2 text-indigo-600 dark:text-indigo-400">Editar</a>
                        @endcan
                        @can('delete', $invoice)
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar esta factura?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400">Eliminar</button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
</x-layouts.app>
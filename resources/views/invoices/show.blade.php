<x-layouts.app :title="__('Detalles de la factura')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Factura #{{ $invoice->id }}</h1>
        <div class="flex space-x-2">
            @can('update', $invoice)
                <a href="{{ route('invoices.edit', $invoice) }}" class="rounded bg-indigo-600 px-4 py-2 text-white">Editar</a>
            @endcan
            <a href="{{ route('invoices.index') }}" class="rounded bg-gray-600 px-4 py-2 text-white">Volver</a>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">ID de Factura</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">#{{ $invoice->id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Mesa</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        @if($invoice->table)
                            <a href="{{ route('tables.show', $invoice->table) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                Mesa {{ $invoice->table->number }}
                            </a>
                            <p class="text-xs text-stone-600 dark:text-stone-400">Capacidad: {{ $invoice->table->capacity }} personas</p>
                        @else
                            No asignada
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Total</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100 font-semibold">{{ number_format($invoice->total, 2) }}€</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Fecha</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">{{ $invoice->date->format('d/m/Y') }}</dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Pedido asociado</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        @if($invoice->order)
                            <a href="{{ route('orders.show', $invoice->order) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                Pedido #{{ $invoice->order->id }}
                            </a>
                            <p class="text-xs text-stone-600 dark:text-stone-400">
                                Tipo: {{ $invoice->order->type }} |
                                Fecha: {{ $invoice->order->date->format('d/m/Y') }}
                            </p>
                        @else
                            No hay pedido asociado
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @can('delete', $invoice)
        <div class="mt-6 bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-stone-900 dark:text-stone-100">Eliminar factura</h3>
                <div class="mt-2 max-w-xl text-sm text-stone-500 dark:text-stone-400">
                    <p>Esta acción no se puede deshacer. Eliminará permanentemente la factura.</p>
                </div>
                <div class="mt-5">
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta factura?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Eliminar factura</button>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</x-layouts.app>
<x-layouts.admin :title="__('Detalles de la factura')">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
                Factura #{{ $invoice->id }}
            </h1>
            <p class="text-sm text-stone-500 dark:text-stone-400">
                Detalle completo de la factura
            </p>
        </div>

        <div class="flex items-center gap-2">
            @can('update', $invoice)
            <a href="{{ route('invoices.edit', $invoice) }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Editar
            </a>
            @endcan

            <a href="{{ route('invoices.index') }}"
                class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600">
                Volver
            </a>
        </div>
    </div>

    <div class="mb-6 rounded-lg border border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">ID de factura</dt>
                    <dd class="mt-1 text-sm font-semibold text-stone-900 dark:text-stone-100">
                        #{{ $invoice->id }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Fecha</dt>
                    <dd class="mt-1 text-sm text-stone-900 dark:text-stone-100">
                        {{ $invoice->date->format('d/m/Y') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Mesa</dt>
                    <dd class="mt-1">
                        @if($invoice->table)
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            Mesa {{ $invoice->table->number }}
                        </span>
                        <p class="mt-1 text-xs text-stone-500 dark:text-stone-400">
                            Capacidad: {{ $invoice->table->capacity }} personas
                        </p>
                        @else
                        <span class="text-sm text-gray-400">Sin mesa asignada</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Total</dt>
                    <dd class="mt-1 text-lg font-semibold text-stone-900 dark:text-stone-100">
                        {{ number_format($invoice->total, 2) }} €
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-stone-500 dark:text-stone-400">Pedido asociado</dt>
                    <dd class="mt-1">
                        @if($invoice->order)
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                Pedido #{{ $invoice->order->id }}
                            </span>

                            <span class="text-xs text-stone-500 dark:text-stone-400">
                                Tipo: {{ ucfirst($invoice->order->type) }} ·
                                {{ $invoice->order->date->format('d/m/Y') }}
                            </span>
                        </div>
                        @else
                        <span class="text-sm text-gray-400">No hay pedido asociado</span>
                        @endif
                    </dd>
                </div>

            </dl>
        </div>
    </div>

    @can('delete', $invoice)
    <div class="rounded-lg border border-red-200 bg-red-50 dark:border-red-900 dark:bg-red-950">
        <div class="p-6">
            <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">
                Zona de peligro
            </h3>

            <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                Eliminar esta factura es una acción permanente y no se puede deshacer.
            </p>

            <form action="{{ route('invoices.destroy', $invoice) }}"
                method="POST"
                class="mt-4"
                onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta factura?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Eliminar factura
                </button>
            </form>
        </div>
    </div>
    @endcan

</x-layouts.admin>
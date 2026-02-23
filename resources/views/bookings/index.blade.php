<x-layouts.admin :title="__('Reservas')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            Reservas
        </h1>

        <a href="{{ route('bookings.create') }}" class="rounded bg-indigo-600 px-3 py-1 text-white">
            Nueva reserva
        </a>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full border border-stone-200 dark:border-stone-700 text-sm">
            <thead class="bg-stone-100 dark:bg-stone-800">
                <tr>
                    <th class="px-4 py-2 text-left">Mesa</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2">Fecha</th>
                    <th class="px-4 py-2">Hora</th>
                    <th class="px-4 py-2">Estado</th>
                    <th class="px-4 py-2 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $booking)
                <tr class="border-t border-stone-200 dark:border-stone-700">
                    <td class="px-4 py-2">{{ $booking->table->name }}</td>
                    <td class="px-4 py-2">{{ $booking->name }}</td>

                    <td class="px-4 py-2 text-center">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-2 text-center">
                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                    </td>

                    <td class="px-4 py-2 text-center">
                        @if($booking->status === 'active')
                        <span class="text-green-600 font-medium">Activa</span>
                        @elseif($booking->status === 'cancelled')
                        <span class="text-red-600 font-medium">Cancelada</span>
                        @else
                        <span class="text-stone-500">Finalizada</span>
                        @endif
                    </td>

                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('bookings.edit', $booking) }}" class="text-indigo-600 hover:underline">
                            Editar
                        </a>

                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Cancelar esta reserva?')">
                                Cancelar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-stone-500">
                        No hay reservas registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
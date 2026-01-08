<x-layouts.app :title="__('Clientes')">
    <div class="py-8">
        <div class="mx-auto max-w-5xl">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-neutral-800">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
                        Clientes
                    </h1>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-stone-700">
                            <th class="border px-4 py-2 text-left">ID</th>
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Email</th>
                            <th class="border px-4 py-2 text-left">Registrado</th>
                            <th class="border px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-stone-800">
                                <td class="border px-4 py-2">{{ $user->id }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="border px-4 py-2 text-center space-x-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="rounded bg-blue-500 px-2 py-1 text-sm text-white">
                                        Editar
                                    </a>

                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded bg-red-600 px-2 py-1 text-sm text-white"
                                                onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="border px-4 py-2 text-center text-gray-500">
                                    No hay clientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>

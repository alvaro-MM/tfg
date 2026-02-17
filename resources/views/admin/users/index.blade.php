<x-layouts.admin :title="__('Clientes')">
    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">
                    Clientes
                </h1>

                <form method="GET" action="{{ route('users.index') }}" class="flex w-full md:w-1/3">
                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre o email"
                        class="flex-1 rounded-l border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-stone-700 dark:border-stone-600 dark:text-white">

                    <button type="submit"
                        class="rounded-r bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700 transition-colors">
                        Buscar
                    </button>
                </form>
            </div>

            @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ session('success') }}
            </div>
            @endif

            <div class="overflow-x-auto rounded-lg shadow bg-white dark:bg-stone-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-stone-700">
                    <thead class="bg-gray-100 dark:bg-stone-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-stone-200 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-stone-200 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-stone-200 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-stone-200 uppercase tracking-wider">Registrado</th>
                            <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-stone-200 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-stone-800 dark:divide-stone-700">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-stone-700 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-stone-100">{{ $user->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-stone-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center space-x-2 flex justify-center">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="rounded bg-blue-500 px-3 py-1 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                                    Editar
                                </a>

                                <form action="{{ route('users.destroy', $user) }}"
                                    method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded bg-red-600 px-3 py-1 text-sm font-medium text-white hover:bg-red-700 transition-colors"
                                        onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                No hay clientes registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($users, 'links'))
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
</x-layouts.admin>
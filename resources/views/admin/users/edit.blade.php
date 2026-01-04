<x-layouts.app :title="__('Editar cliente')">
    <div class="py-8">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-neutral-800">
                <h1 class="mb-6 text-2xl font-semibold text-stone-900 dark:text-stone-100">
                    Editar cliente
                </h1>

                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Nombre
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="mt-1 w-full rounded border-gray-300 dark:border-stone-600 dark:bg-stone-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Email
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="mt-1 w-full rounded border-gray-300 dark:border-stone-600 dark:bg-stone-700 dark:text-white">
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <a href="{{ route('admin.users.index') }}"
                           class="rounded bg-gray-500 px-4 py-2 text-white">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="rounded bg-blue-600 px-4 py-2 text-white">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

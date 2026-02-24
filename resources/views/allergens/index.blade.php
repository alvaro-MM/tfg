<x-layouts.admin :title="__('Alérgenos')">
    <div class="py-8">
        <div class="mx-auto max-w-6xl">
            <div class="rounded-xl bg-white p-6 shadow-md dark:bg-neutral-800">

                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">
                        Alérgenos
                    </h1>

                    <a href="{{ route('allergens.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-green-700 transition">
                        + Nuevo alérgeno
                    </a>
                </div>

                @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-stone-100 text-stone-700 dark:bg-stone-700 dark:text-stone-200">
                                <th class="px-4 py-3 text-left font-semibold">Imagen</th>
                                <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold">Descripción</th>
                                <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-200 dark:divide-stone-700">
                            @forelse($allergens as $allergen)
                            <tr class="hover:bg-stone-50 dark:hover:bg-stone-800 transition">

                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <img
                                            src="{{ asset($allergen->image) }}"
                                            alt="{{ $allergen->name }}"
                                            class="h-12 w-12 rounded-full object-cover ring-2 ring-stone-200 dark:ring-stone-600 shadow">
                                    </div>
                                </td>

                                <td class="px-4 py-3 font-medium text-stone-900 dark:text-stone-100">
                                    {{ $allergen->name }}
                                </td>

                                <td class="px-4 py-3 text-stone-600 dark:text-stone-300 max-w-md">
                                    {{ Str::limit($allergen->description, 90) }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('allergens.edit', $allergen) }}"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 transition text-xs font-medium">
                                            Editar
                                        </a>

                                        <form action="{{ route('allergens.destroy', $allergen) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg bg-red-600 px-3 py-1.5 text-white hover:bg-red-700 transition text-xs font-medium"
                                                onclick="return confirm('¿Seguro que quieres eliminar este alérgeno?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-stone-500">
                                    No hay alérgenos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-layouts.admin>
<x-layouts.app :title="__('Alérgenos')">
    <div class="py-8">
        <div class="mx-auto max-w-4xl">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-neutral-800">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Alérgenos</h1>
                    <a href="{{ route('allergens.create') }}" 
                       class="rounded bg-green-600 px-4 py-2 text-white">Nuevo alérgeno</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-stone-700">
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Descripción</th>
                            <th class="border px-4 py-2 text-left">Imagen</th>
                            <th class="border px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allergens as $allergen)
                            <tr class="hover:bg-gray-50 dark:hover:bg-stone-800">
                                <td class="border px-4 py-2">{{ $allergen->name }}</td>
                                <td class="border px-4 py-2">{{ $allergen->description }}</td>
                                <td class="border px-4 py-2">
                                    @if($allergen->image)
                                        <img src="{{ $allergen->image }}" alt="{{ $allergen->name }}" class="h-10">
                                    @endif
                                </td>
                                <td class="border px-4 py-2 text-center space-x-2">
                                    <a href="{{ route('allergens.edit', $allergen) }}" 
                                       class="rounded bg-blue-500 px-2 py-1 text-white text-sm">Editar</a>
                                    <form action="{{ route('allergens.destroy', $allergen) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="rounded bg-red-600 px-2 py-1 text-white text-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar este alérgeno?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border px-4 py-2 text-center text-gray-500">No hay alérgenos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>

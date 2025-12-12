<x-layouts.app :title="'Editar Mesa'">

    <h1 class="text-2xl font-semibold mb-4">Editar Mesa</h1>

    <form action="{{ route('tables.update', $table) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        @include('tables._form')

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
        <a href="{{ route('tables.index') }}" class="ml-3 text-gray-600">Cancelar</a>
    </form>

</x-layouts.app>

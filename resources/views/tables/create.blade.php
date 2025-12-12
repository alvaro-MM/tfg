<x-layouts.app :title="'Nueva Mesa'">

    <h1 class="text-2xl font-semibold mb-4">Crear Mesa</h1>

    <form action="{{ route('tables.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('tables._form')

        <button class="bg-green-600 text-white px-4 py-2 rounded">Crear</button>
        <a href="{{ route('tables.index') }}" class="ml-3 text-gray-600">Cancelar</a>
    </form>

</x-layouts.app>

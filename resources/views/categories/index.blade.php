<x-layout title="Categorías">
    <h1>Categorías</h1>

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Crear nueva categoría</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría Padre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '—' }}</td>
                    <td>{{ $category->parent?->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No hay categorías registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-layout>
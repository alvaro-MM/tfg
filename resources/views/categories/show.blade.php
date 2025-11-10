<x-layout title="Detalle de Categoría">
    <h1>{{ $category->name }}</h1>

    <p><strong>Nombre:</strong> {{ $category->name }}</p>
    <p><strong>Descripción:</strong> {{ $category->description ?? 'Sin descripción' }}</p>
    <p><strong>Categoría padre:</strong> {{ $category->parent?->name ?? '—' }}</p>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Volver</a>
</x-layout>
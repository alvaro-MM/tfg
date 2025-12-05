<x-layout title="Detalle de Review">
    <h1>{{ $review->name }}</h1>

    <p><strong>Nombre:</strong> {{ $review->name }}</p>
    <p><strong>Descripción:</strong> {{ $review->description ?? 'Sin descripción' }}</p>

    <a href="{{ route('review.index') }}" class="btn btn-secondary">Volver</a>
</x-layout>

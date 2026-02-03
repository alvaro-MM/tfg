@extends('layout.tfg')

@section('title', 'Platos')

@section('content')
    <div class="max-w-7xl mx-auto py-16 px-6">

        <h1 class="text-4xl font-extrabold text-center text-red-600 mb-12">
            Nuestros Platos
        </h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($dishes as $dish)
                <div class="dish-card">
                    <h3 class="text-xl font-bold">{{ $dish->name }}</h3>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $dish->category->name ?? 'Sin categoría' }}
                    </p>

                    <p class="mt-3 text-gray-700 text-sm">
                        {{ $dish->description }}
                    </p>

                    @if($dish->allergens->count())
                        <div class="mt-4 text-xs text-red-600">
                            ⚠️ Alérgenos:
                            {{ $dish->allergens->pluck('name')->join(', ') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .dish-card {
            @apply bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition border-l-4 border-red-500;
        }
    </style>
@endsection

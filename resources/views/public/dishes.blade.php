@extends('layout.tfg')

@section('title', 'Platos')

@section('content')
    <div class="max-w-7xl mx-auto py-16 px-6">

        <h1 class="text-5xl font-extrabold text-center text-red-600 mb-16 drop-shadow-md">
            Nuestros Platos
        </h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($dishes as $dish)
                <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition border-l-4 border-red-500 transform hover:scale-105">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $dish->name }}</h3>

                    <p class="text-sm text-gray-500 mt-1 mb-2">
                        {{ $dish->category->name ?? 'Sin categoría' }}
                    </p>

                    <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                        {{ $dish->description }}
                    </p>

                    @if($dish->allergens->count())
                        <div class="mt-4 text-xs text-red-600 font-medium">
                            ⚠️ Alérgenos: {{ $dish->allergens->pluck('name')->join(', ') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection

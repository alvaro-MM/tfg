@extends('layout.tfg')

@section('title', 'Detalle de la review')

@section('content')

    <div class="w-full max-w-5xl mx-auto p-8">

        {{-- Título --}}
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white mb-8">
            {{ $review->name }}
        </h1>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl overflow-hidden">

            {{-- Imagen --}}
            @if($review->image)
                <div class="w-full h-80 overflow-hidden bg-gray-100 dark:bg-zinc-700">
                    <img
                        src="{{ asset('storage/' . $review->image) }}"
                        alt="{{ $review->name }}"
                        class="w-full h-full object-cover transition duration-300 hover:scale-105"
                    >
                </div>
            @endif

            <div class="p-8 space-y-6">

                {{-- Información principal --}}
                <div class="grid md:grid-cols-2 gap-6">

                    {{-- Descripción --}}
                    <div>
                        <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 tracking-wider">
                            Descripción
                        </h2>
                        <p class="mt-2 text-stone-800 dark:text-stone-100 leading-relaxed">
                            {{ $review->description ?? 'Sin descripción' }}
                        </p>
                    </div>

                    {{-- Datos adicionales --}}
                    <div class="space-y-4">

                        @if($review->user)
                            <div>
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    Usuario
                                </span>
                                <p class="text-stone-900 dark:text-white">
                                    👤 {{ $review->user->name }}
                                </p>
                            </div>
                        @endif

                        @if($review->dish)
                            <div>
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    Plato
                                </span>
                                <div class="mt-1 inline-block bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 px-3 py-1 rounded-full text-sm font-medium">
                                    🍽️ {{ $review->dish->name }}
                                </div>
                            </div>
                        @endif

                        @if($review->drink)
                            <div>
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    Bebida
                                </span>
                                <div class="mt-1 inline-block bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
                                    🥤 {{ $review->drink->name }}
                                </div>
                            </div>
                        @endif

                        {{-- Rating --}}
                        @if($review->rating)
                            <div>
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    Valoración
                                </span>
                                <div class="flex items-center gap-2 mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-2xl {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                            ★
                                        </span>
                                    @endfor
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        ({{ $review->rating }}/5)
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Botón --}}
                <div class="pt-6 border-t border-gray-200 dark:border-zinc-700">
                    <a href="{{ route('review.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-700 hover:bg-gray-800
                              text-white font-semibold rounded-xl shadow-md
                              transition duration-200">
                        ← Volver al listado
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection

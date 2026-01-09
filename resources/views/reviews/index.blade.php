{{--@extends('layouts.app')--}}

{{--@section('content')--}}
<x-layouts.admin :title="__('Review')">
    <div class="max-w-5xl mx-auto py-8">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-stone-100 mb-6">
            Listado de Reviews
        </h1>

        <div class="flex justify-end mb-4">
            <a href="{{ route('review.create') }}"
               class="px-4 py-2 bg-green-600 text-gray-800 dark:text-white rounded-md shadow hover:bg-green-700 transition">
                Crear nueva Review
            </a>
        </div>

{{--        --}}{{-- Filtros / Buscador --}}
{{--        <div class="mb-6 p-4 rounded-lg bg-white dark:bg-zinc-800 shadow-sm border border-gray-200 dark:border-zinc-700">--}}
{{--            <form method="GET" class="space-y-4">--}}
{{--                <div>--}}
{{--                    <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Buscar</label>--}}
{{--                    <input type="text"--}}
{{--                           name="search"--}}
{{--                           value="{{ request('search') }}"--}}
{{--                           placeholder="Buscar por título, usuario o juego..."--}}
{{--                           class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">--}}
{{--                </div>--}}

{{--                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">--}}
{{--                    <div>--}}
{{--                        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Juego</label>--}}
{{--                        <select name="game_id"--}}
{{--                                class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">--}}
{{--                            <option value="">Todos</option>--}}
{{--                            @foreach($games as $game)--}}
{{--                                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>--}}
{{--                                    {{ $game->title }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <div>--}}
{{--                        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Ordenar por</label>--}}
{{--                        <select name="sort"--}}
{{--                                class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">--}}
{{--                            <option value="">Predeterminado</option>--}}
{{--                            <option value="rating_desc" {{ request('sort')=='rating_desc' ? 'selected' : '' }}>Rating ↓</option>--}}
{{--                            <option value="rating_asc" {{ request('sort')=='rating_asc' ? 'selected' : '' }}>Rating ↑</option>--}}
{{--                            <option value="date_desc" {{ request('sort')=='date_desc' ? 'selected' : '' }}>Más recientes</option>--}}
{{--                            <option value="date_asc" {{ request('sort')=='date_asc' ? 'selected' : '' }}>Más antiguas</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <div>--}}
{{--                        <label class="block text-sm font-medium text-gray-700 dark:text-stone-200">Rating mínimo</label>--}}
{{--                        <input type="number" step="1" min="1" max="10"--}}
{{--                               name="min_rating"--}}
{{--                               value="{{ request('min_rating') }}"--}}
{{--                               class="mt-1 block w-full rounded-md border-gray-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-stone-100 shadow-sm">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <button class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">--}}
{{--                    Filtrar--}}
{{--                </button>--}}
{{--            </form>--}}
{{--        </div>--}}

        {{-- Listado --}}
        <div class="grid gap-4">
            @forelse ($reviews as $review)
                <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 shadow-sm border border-gray-200 dark:border-zinc-700">

                    <div class="flex justify-between items-start">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-stone-100">
                            {{ $review->name }}
                        </h2>

{{--                        <span class="px-2 py-1 text-sm rounded bg-gray-100 dark:bg-zinc-700 dark:text-stone-200">--}}
{{--                        ⭐ {{ $review->rating }}/10--}}
{{--                    </span>--}}
                    </div>



                    <p class="mt-2 text-gray-700 dark:text-stone-200">
                        {{ Str::limit($review->content, 150) }}
                    </p>

                    <div class="mt-3 flex justify-between items-center text-sm">
                    <span class="text-gray-500 dark:text-stone-400">
                        Por: {{ $review->user->name }}
                    </span>

                        <div class="flex gap-2">
                            <a href="{{ route('review.show', $review) }}"
                               class="text-blue-600 hover:underline">Ver</a>

                            <a href="{{ route('review.edit', $review) }}"
                               class="text-yellow-600 hover:underline">Editar</a>

                            <form action="{{ route('review.destroy', $review) }}" method="POST" onsubmit="return confirm('¿Eliminar review?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <p class="text-gray-600 dark:text-stone-300 text-center py-10">
                    No hay reviews todavía.
                </p>
            @endforelse
        </div>

        {{-- Paginación --}}
{{--        <div class="mt-6">--}}
{{--            {{ $reviews->links() }}--}}
{{--        </div>--}}
    </div>
</x-layouts.admin>
{{--@endsection--}}

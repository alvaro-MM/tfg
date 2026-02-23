@extends('layout.tfg')

@section('title', 'Bebidas')

@section('content')
    <div class="max-w-7xl mx-auto py-16 px-6">

        <h1 class="text-5xl font-extrabold text-center text-red-600 mb-16 drop-shadow-md">
            Nuestras Bebidas
        </h1>

        @livewire('public.drinks-list')

    </div>
@endsection

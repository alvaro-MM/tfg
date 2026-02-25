@extends('layouts.public')

@section('title', 'Gracias por tu visita')

@section('header-title', '¡Gracias!')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div class="text-center space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">¡Pago completado correctamente!</h2>
                <p class="mt-2 text-base text-gray-700">
                    Muchas gracias por tu visita{{ $table->name ? ' en la mesa ' . $table->name : '' }}.
                </p>
            </div>

            <div class="mt-4">
                <p class="text-sm text-gray-500">
                    El personal ya tiene registrado tu pago. Esperamos volver a verte pronto.
                </p>
            </div>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md">
                <a href="{{ route('home') }}">
                    Volver al inicio
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </button>
        </div>
    </div>
</div>
@endsection


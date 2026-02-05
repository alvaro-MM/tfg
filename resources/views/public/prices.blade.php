@extends('layout.tfg')

@section('title', 'Precios')

@section('content')
    <div class="max-w-7xl mx-auto py-16 px-6">

        <h1 class="text-5xl font-extrabold text-center text-red-600 mb-16 drop-shadow-md">
            Menús y Precios
        </h1>

        <div class="grid md:grid-cols-2 gap-10">

            {{-- Menú Día --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg p-8 text-center border-t-4 border-red-500 transform hover:scale-105 transition">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">🍱 Menú del Día</h2>
                <p class="text-4xl font-extrabold text-red-600 mb-4">14,95 €</p>
                <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-4">
                    <li>Buffet libre</li>
                    <li>Bebida incluida</li>
                    <li>Postre incluido</li>
                </ul>
                <span class="text-sm text-gray-500 italic">Lunes a Viernes (13:00 - 16:00)</span>
            </div>

            {{-- Menú Noche --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg p-8 text-center border-t-4 border-red-500 transform hover:scale-105 transition">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">🌙 Menú Noche</h2>
                <p class="text-4xl font-extrabold text-red-600 mb-4">22,95 €</p>
                <ul class="text-gray-700 dark:text-gray-300 space-y-2 mb-4">
                    <li>Buffet libre premium</li>
                    <li>Platos especiales incluidos</li>
                    <li>Postres japoneses</li>
                </ul>
                <span class="text-sm text-gray-500 italic">Todos los días (20:00 - 23:30)</span>
            </div>

            {{-- Fin de semana --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg p-8 text-center border-t-4 border-red-500 md:col-span-2 transform hover:scale-105 transition">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">🎉 Fin de Semana</h2>
                <p class="text-4xl font-extrabold text-red-600 mb-4">24,95 €</p>
                <ul class="text-gray-700 dark:text-gray-300 grid md:grid-cols-2 gap-2 mb-4">
                    <li>Buffet completo</li>
                    <li>Bebida no incluida</li>
                    <li>Especialidades del chef</li>
                    <li>Sin límite de pedidos</li>
                </ul>
                <span class="text-sm text-gray-500 italic">Sábados y Domingos</span>
            </div>

        </div>
    </div>
@endsection

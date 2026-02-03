@extends('layout.tfg')

@section('title', 'Precios')

@section('content')
    <div class="max-w-7xl mx-auto py-16 px-6">

        <h1 class="text-4xl font-extrabold text-center text-red-600 mb-12">
            Menús y Precios
        </h1>

        <div class="grid md:grid-cols-2 gap-10">

            {{-- Menú Día --}}
            <div class="price-card">
                <h2 class="text-2xl font-bold text-gray-900">🍱 Menú del Día</h2>
                <p class="price">14,95 €</p>
                <ul class="list">
                    <li>Buffet libre</li>
                    <li>Bebida incluida</li>
                    <li>Postre incluido</li>
                </ul>
                <span class="time">Lunes a Viernes (13:00 - 16:00)</span>
            </div>

            {{-- Menú Noche --}}
            <div class="price-card">
                <h2 class="text-2xl font-bold text-gray-900">🌙 Menú Noche</h2>
                <p class="price">22,95 €</p>
                <ul class="list">
                    <li>Buffet libre premium</li>
                    <li>Platos especiales incluidos</li>
                    <li>Postres japoneses</li>
                </ul>
                <span class="time">Todos los días (20:00 - 23:30)</span>
            </div>

            {{-- Fin de semana --}}
            <div class="price-card md:col-span-2">
                <h2 class="text-2xl font-bold text-gray-900">🎉 Fin de Semana</h2>
                <p class="price">24,95 €</p>
                <ul class="list grid md:grid-cols-2 gap-2">
                    <li>Buffet completo</li>
                    <li>Bebida no incluida</li>
                    <li>Especialidades del chef</li>
                    <li>Sin límite de pedidos</li>
                </ul>
                <span class="time">Sábados y Domingos</span>
            </div>

        </div>
    </div>

    <style>
        .price-card {
            @apply bg-white rounded-xl shadow-lg p-8 text-center border-t-4 border-red-500;
        }
        .price {
            @apply text-4xl font-extrabold text-red-600 my-4;
        }
        .list {
            @apply text-gray-700 space-y-1 my-4;
        }
        .time {
            @apply text-sm text-gray-500 italic;
        }
    </style>
@endsection

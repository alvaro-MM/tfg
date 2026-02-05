@extends('layout.tfg')

@section('title', 'Sobre Nosotros')

@section('content')
    <div class="max-w-6xl mx-auto py-16 px-6">

        <h1 class="text-5xl font-extrabold text-center text-red-600 mb-16 drop-shadow-md">
            Sobre Sushi Buffet
        </h1>

        <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg p-10 space-y-8">

            <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                En <strong>Sushi Buffet</strong> ofrecemos una experiencia gastronómica japonesa auténtica
                basada en calidad, variedad y frescura.
            </p>

            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                Nuestro restaurante combina tradición japonesa con un ambiente moderno,
                ofreciendo un buffet libre donde puedes disfrutar sin límites.
            </p>

            <div class="grid md:grid-cols-2 gap-10 mt-8">

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">📍 Dirección</h2>
                    <p>Calle Sakura 123, Barcelona</p>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">📞 Contacto</h2>
                    <p>Teléfono: 666 777 888</p>
                    <p>Email: contacto@sushibuffet.com</p>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">🕒 Horarios</h2>
                    <p>Lunes a Viernes: 13:00 - 23:30</p>
                    <p>Sábados y Domingos: 13:00 - 00:00</p>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">🍣 Nuestra filosofía</h2>
                    <p>Producto fresco, respeto por la tradición y pasión por el detalle.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

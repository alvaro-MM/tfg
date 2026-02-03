@extends('layout.tfg')

@section('title', 'Sobre Nosotros')

@section('content')
    <div class="max-w-6xl mx-auto py-16 px-6">

        <h1 class="text-4xl font-extrabold text-center text-red-600 mb-12">
            Sobre Sushi Buffet
        </h1>

        <div class="bg-white rounded-xl shadow-lg p-10 space-y-6">

            <p class="text-gray-700 text-lg">
                En <strong>Sushi Buffet</strong> ofrecemos una experiencia gastronómica japonesa
                auténtica basada en calidad, variedad y frescura.
            </p>

            <p class="text-gray-700">
                Nuestro restaurante combina tradición japonesa con un ambiente moderno,
                ofreciendo un buffet libre donde puedes disfrutar sin límites.
            </p>

            <div class="grid md:grid-cols-2 gap-8 mt-8">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">📍 Dirección</h2>
                    <p>Calle Sakura 123, Barcelona</p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">📞 Contacto</h2>
                    <p>Teléfono: 666 777 888</p>
                    <p>Email: contacto@sushibuffet.com</p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">🕒 Horarios</h2>
                    <p>Lunes a Viernes: 13:00 - 23:30</p>
                    <p>Sábados y Domingos: 13:00 - 00:00</p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">🍣 Nuestra filosofía</h2>
                    <p>Producto fresco, respeto por la tradición y pasión por el detalle.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

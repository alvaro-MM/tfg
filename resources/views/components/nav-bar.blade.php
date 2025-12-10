<!-- Sushi Navigation -->
<nav class="bg-black shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center">
                <img src="{{ asset('images/sushi-logo.png') }}" class="h-14 mr-3 rounded-full" alt="logo">
                <a href="/" class="text-red-500 text-2xl font-bold tracking-wide">
                    Sushi Buffet
                </a>
            </div>

            <!-- Links -->
            <div class="hidden md:flex space-x-6">
                <a href="/" class="nav-link">Inicio</a>
{{--                <a href="{{ route('menu') }}" class="nav-link">Menú</a>--}}
{{--                <a href="{{ route('reservas') }}" class="nav-link">Reservas</a>--}}
{{--                <a href="{{ route('reviews.index') }}" class="nav-link">Reseñas</a>--}}
{{--                <a href="{{ route('contact') }}" class="nav-link">Contacto</a>--}}
            </div>

            <!-- Auth -->
            <div class="hidden md:flex space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Entrar
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                        Registrarse
                    </a>
                @else
                    <div class="relative">
                        <!-- Botón using peer -->
                        <button class="peer flex items-center text-white bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-md">
                            {{ Auth::user()->name }}
                        </button>

                        <!-- Dropdown -->
                        <div class="hidden peer-hover:block hover:block absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">

                            <a href="{{ route('dashboard') }}" class="dropdown-item block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Dashboard
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Salir
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

        </div>
    </div>
</nav>

<style>
    .nav-link {
        @apply text-gray-300 hover:text-red-500 text-sm font-semibold transition;
    }
    .dropdown-item {
        @apply px-4 py-2 text-gray-700 hover:bg-gray-100 w-full text-left;
    }
</style>

<nav class="bg-black border-b border-gray-800 shadow-xl">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex h-20 items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/sushi-logo.png') }}"
                     class="h-12 w-12 rounded-full border border-red-600"
                     alt="logo">

                <a href="{{ route('home') }}"
                   class="text-2xl font-extrabold tracking-wide text-red-500 hover:text-red-400 transition">
                    Sushi Buffet
                </a>
            </div>

            {{-- LINKS PRINCIPALES --}}
            <div class="hidden md:flex items-center gap-6 text-white hover:text-white">
                <a href="{{ route('home') }}" class="nav-link">Inicio</a>
                <a href="{{ route('dishes.public') }}" class="nav-link">Platos</a>
                <a href="{{ route('prices') }}" class="nav-link">Precios</a>
                <a href="{{ route('about') }}" class="nav-link">Sobre nosotros</a>
            </div>

            {{-- AUTH --}}
            <div class="flex items-center gap-4">

                @guest
                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
                        Entrar
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-lg border border-yellow-500 px-4 py-2 text-sm font-semibold text-yellow-400 hover:bg-yellow-500 hover:text-black transition">
                        Registrarse
                    </a>
                @else
                    {{-- USER DROPDOWN --}}
                    <div class="relative dropdown-container">

                        <button onclick="toggleDropdown()"
                            class="flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-700 transition">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 text-gray-400 group-hover:rotate-180 transition"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- DROPDOWN --}}
                        <div id="dropdownMenu"
                             class="hidden absolute right-0 mt-3 w-60 rounded-xl bg-white shadow-2xl ring-1 ring-black/5 z-20">

                            {{-- Header --}}
                            <div class="px-4 py-3 text-sm text-gray-500 border-b">
                                Panel de usuario
                            </div>

                            {{-- Dashboard --}}
                            <a href="{{ route('dashboard') }}"
                               class="dropdown-item">
                                🧭 Dashboard
                            </a>

                            <div class="border-t my-1"></div>

                            {{-- Gestión --}}
                            <a href="{{ route('categories.index') }}" class="dropdown-item">📂 Categorías</a>
                            <a href="{{ route('dishes.index') }}" class="dropdown-item">🍣 Platos</a>
                            <a href="{{ route('drinks.index') }}" class="dropdown-item">🥤 Bebidas</a>
                            <a href="{{ route('allergens.index') }}" class="dropdown-item">⚠️ Alérgenos</a>
                            <a href="{{ route('tables.index') }}" class="dropdown-item">🪑 Mesas</a>
                            <a href="{{ route('review.index') }}" class="dropdown-item">⭐ Reseñas</a>

                            <div class="border-t my-1"></div>

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="dropdown-item w-full text-left text-red-600 hover:bg-red-50">
                                    🚪 Salir
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
        @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition;
    }
</style>

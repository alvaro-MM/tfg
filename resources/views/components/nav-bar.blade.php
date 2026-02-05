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
            <div class="hidden md:flex items-center gap-4">
                @guest
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center px-6 py-2 rounded-full text-sm font-semibold
              bg-gray-800 text-white shadow-md hover:bg-gray-700 hover:shadow-lg
              transition-all duration-300 transform hover:-translate-y-0.5">
                    Entrar
                </a>

                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-6 py-2 rounded-full text-sm font-semibold
              bg-red-600 text-white shadow-md hover:bg-red-700 hover:shadow-lg
              transition-all duration-300 transform hover:-translate-y-0.5">
                    Registrarse
                </a>
                @else
                <div class="relative">

                    <button
                        id="userDropdownButton"
                        class="inline-flex items-center justify-center gap-2
                                   rounded-full bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white
                                   hover:bg-gray-700 transition focus:outline-none">
                        {{ Auth::user()->name }}

                        <svg class="w-4 h-4 opacity-70"
                            fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="userDropdown"
                        class="hidden absolute right-0 mt-4 w-72
                                    rounded-xl bg-white shadow-xl ring-1 ring-black/10 z-50">

                        <div class="p-3">
                            <div class="flex items-center gap-3 rounded-lg bg-gray-100 px-3 py-2">
                                <img class="h-9 w-9 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                    alt="Avatar">

                                <div class="text-sm leading-tight">
                                    <div class="font-semibold text-gray-800">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate">
                                        Sesión activa
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="px-2 pb-2 text-sm font-medium text-gray-700 space-y-2">

                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="dropdown-link flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400"
                                        fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z" />
                                    </svg>
                                    Panel de control
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('profile.edit') }}"
                                    class="dropdown-link flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400"
                                        fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06
                                                     a2 2 0 01-2.83 2.83l-.06-.06
                                                     a1.65 1.65 0 00-1.82-.33
                                                     a1.65 1.65 0 00-1 1.51V21
                                                     a2 2 0 01-4 0v-.09
                                                     a1.65 1.65 0 00-1-1.51
                                                     a1.65 1.65 0 00-1.82.33l-.06.06
                                                     a2 2 0 01-2.83-2.83l.06-.06
                                                     a1.65 1.65 0 00.33-1.82
                                                     a1.65 1.65 0 00-1.51-1H3
                                                     a2 2 0 010-4h.09
                                                     a1.65 1.65 0 001.51-1
                                                     a1.65 1.65 0 00-.33-1.82l-.06-.06
                                                     a2 2 0 012.83-2.83l.06.06
                                                     a1.65 1.65 0 001.82.33H9
                                                     a1.65 1.65 0 001-1.51V3
                                                     a2 2 0 014 0v.09
                                                     a1.65 1.65 0 001 1.51
                                                     a1.65 1.65 0 001.82-.33l.06-.06
                                                     a2 2 0 012.83 2.83l-.06.06
                                                     a1.65 1.65 0 00-.33 1.82V9
                                                     a1.65 1.65 0 001.51 1H21
                                                     a2 2 0 010 4h-.09
                                                     a1.65 1.65 0 00-1.51 1z" />
                                    </svg>
                                    Configuración
                                </a>
                            </li>

                            <li class="my-2 border-t"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-link flex items-center gap-2 text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7
                                                         a3 3 0 00-3 3v10a3 3 0 003 3h2" />
                                        </svg>
                                        Salir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    .btn-guest {
        @apply px-5 py-2 rounded-md font-semibold transition-all text-sm shadow-sm;
    }

    .btn-guest:hover {
        @apply scale-105;
    }

    .btn-guest.bg-gray-800 {
        @apply bg-gray-800 text-white hover:bg-gray-700;
    }

    .btn-guest.bg-red-600 {
        @apply bg-red-600 text-white hover:bg-red-700;
    }

    .dropdown-link {
        @apply w-full px-4 py-3 flex items-center gap-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('userDropdownButton');
        const dropdown = document.getElementById('userDropdown');

        button.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            dropdown.classList.add('hidden');
        });
    });
</script>
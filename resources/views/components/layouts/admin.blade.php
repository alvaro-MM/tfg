<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }}</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/admin-dashboard.js'
    ])
</head>

<body class="flex h-screen bg-gray-100">

    <aside
        x-data="{
    openAdmin: localStorage.getItem('openAdmin') === 'true',
    openPerformance: localStorage.getItem('openPerformance') === 'true',

    toggleAdmin() {
        this.openAdmin = !this.openAdmin
        localStorage.setItem('openAdmin', this.openAdmin)
    },

    togglePerformance() {
        this.openPerformance = !this.openPerformance
        localStorage.setItem('openPerformance', this.openPerformance)
    }
}"
        class="hidden md:flex flex-col w-64 bg-gray-900 text-gray-300">


        <div class="flex items-center justify-center h-16 border-b border-gray-800">
            <span class="text-white font-semibold tracking-wide">
                Panel Admin
            </span>
        </div>

        <nav
            class="flex-1 px-3 py-4 text-sm space-y-2
           overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900">

            <button
                @click="toggleAdmin()"
                class="w-full flex items-center justify-between px-3 py-2 rounded
           text-gray-200 hover:bg-gray-800 transition">


                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657 5.657l-1.414-1.414M7.757 7.757L6.343 6.343m11.314 0l-1.414 1.414M7.757 16.243l-1.414 1.414" />
                    </svg>
                    <span class="font-medium">Administrar</span>
                </div>

                <svg
                    class="h-4 w-4 transition-transform duration-200"
                    :class="{ 'rotate-180': openAdmin }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div
                x-show="openAdmin"
                x-transition
                x-cloak
                class="ml-4 mt-1 space-y-1 border-l border-gray-800 pl-4">

                <a href="{{ route('users.index') }}"
                    class="sidebar-sub {{ request()->routeIs('users.*') ? 'sidebar-sub-active' : '' }}">
                    Usuarios
                </a>

                <a href="{{ route('tables.index') }}"
                    class="sidebar-sub {{ request()->routeIs('tables.*') ? 'sidebar-sub-active' : '' }}">
                    Mesas
                </a>

                <a href="{{ route('dishes.index') }}"
                    class="sidebar-sub {{ request()->routeIs('dishes.*') ? 'sidebar-sub-active' : '' }}">
                    Platos
                </a>

                <a href="{{ route('drinks.index') }}"
                    class="sidebar-sub {{ request()->routeIs('drinks.*') ? 'sidebar-sub-active' : '' }}">
                    Bebidas
                </a>

                <a href="{{ route('categories.index') }}"
                    class="sidebar-sub {{ request()->routeIs('categories.*') ? 'sidebar-sub-active' : '' }}">
                    Categorías
                </a>

                <a href="{{ route('allergens.index') }}"
                    class="sidebar-sub {{ request()->routeIs('allergens.*') ? 'sidebar-sub-active' : '' }}">
                    Alérgenos
                </a>

                <a href="{{ route('review.index') }}"
                    class="sidebar-sub {{ request()->routeIs('review.*') ? 'sidebar-sub-active' : '' }}">
                    Reseñas
                </a>

                <a href="{{ route('menus.index') }}"
                    class="sidebar-sub {{ request()->routeIs('menus.*') ? 'sidebar-sub-active' : '' }}">
                    Menús
                </a>

                <a href="{{ route('offers.index') }}"
                    class="sidebar-sub {{ request()->routeIs('offers.*') ? 'sidebar-sub-active' : '' }}">
                    Ofertas
                </a>

                <a href="{{ route('invoices.index') }}"
                    class="sidebar-sub {{ request()->routeIs('invoices.*') ? 'sidebar-sub-active' : '' }}">
                    Facturas
                </a>

            </div>

            <button
                @click="togglePerformance()"
                class="w-full flex items-center justify-between px-3 py-2 rounded
           text-gray-200 hover:bg-gray-800 transition">

                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3v18h18M7 14l4-4 4 3 5-6" />
                    </svg>
                    <span class="font-medium">Rendimiento</span>
                </div>

                <svg
                    class="h-4 w-4 transition-transform duration-200"
                    :class="{ 'rotate-180': openPerformance }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div
                x-show="openPerformance"
                x-transition
                x-cloak
                class="ml-4 mt-1 space-y-1 border-l border-gray-800 pl-4">

                <a href="{{ route('admin-dashboard.index') }}"
                    class="sidebar-sub {{ request()->routeIs('admin-dashboard.index') ? 'sidebar-sub-active' : '' }}">
                    Hoy
                </a>

                <a href="{{ route('performance') }}"
                    class="sidebar-sub {{ request()->routeIs('performance') ? 'sidebar-sub-active' : '' }}">
                    General
                </a>
            </div>

        </nav>

        <div class="border-t border-gray-800 p-3 space-y-1">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-3 py-2 rounded
           text-gray-400 hover:text-white hover:bg-gray-800 transition">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-3.314 0-6 1.686-6 3.75V20h12v-2.25c0-2.064-2.686-3.75-6-3.75z" />
                </svg>

                <span>Perfil</span>
            </a>

            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-3 py-2 rounded
               text-gray-400 hover:text-white hover:bg-gray-800 transition">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
                </svg>

                <span>Volver al inicio</span>
            </a>
        </div>

    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        {{ $slot }}
    </main>
</body>

</html>
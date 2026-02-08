<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Staff' }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="flex h-screen bg-gray-100">

{{-- Sidebar --}}
<aside
    x-data="{
            openTables: localStorage.getItem('openTables') === 'true',

            toggleTables() {
                this.openTables = !this.openTables
                localStorage.setItem('openTables', this.openTables)
            }
        }"
    class="hidden md:flex flex-col w-64 bg-gray-900 text-gray-300">

    <div class="flex items-center justify-center h-16 border-b border-gray-800">
        <span class="text-white font-semibold tracking-wide">Panel Staff</span>
    </div>

    <nav class="flex-1 px-3 py-4 text-sm space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900">

        {{-- Dashboard --}}
        <a href="{{ route('staff-dashboard.index') }}"
           class="w-full flex items-center gap-3 px-3 py-2 rounded
               transition
               {{ request()->routeIs('staff.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:bg-gray-800' }}">
            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3h18v18H3V3z" />
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Mesas --}}
        <button @click="toggleTables()"
                class="w-full flex items-center justify-between px-3 py-2 rounded
                           text-gray-200 hover:bg-gray-800 transition">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Mesas</span>
            </div>
            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openTables }" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="openTables" x-transition x-cloak class="ml-4 mt-1 space-y-1 border-l border-gray-800 pl-4">
            <a href="{{ route('staff-tables.index') }}"
               class="sidebar-sub {{ request()->routeIs('staff-tables.*') ? 'sidebar-sub-active' : '' }}">
                Ver Mesas
            </a>
            {{-- Puedes agregar más acciones relacionadas con mesas --}}
        </div>

        {{-- Pedidos --}}
        <a href="{{ route('staff-orders.index') }}"
           class="w-full flex items-center gap-3 px-3 py-2 rounded
               transition
               {{ request()->routeIs('staff-orders.*') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:bg-gray-800' }}">
            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7h18M3 12h18M3 17h18" />
            </svg>
            <span>Pedidos</span>
        </a>

    </nav>

    {{-- Footer --}}
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

{{-- Main Content --}}
<main class="flex-1 p-8 overflow-y-auto">
    {{ $slot }}
</main>

<style>
    .sidebar-sub {
        @apply block px-3 py-2 rounded hover:bg-gray-800 text-gray-200 transition;
    }
    .sidebar-sub-active {
        @apply bg-gray-800 text-white font-medium;
    }
</style>
</body>
</html>

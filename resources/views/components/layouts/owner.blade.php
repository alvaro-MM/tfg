@props(['title' => 'Owner'])

    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} - Panel Owner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-zinc-900 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white dark:bg-zinc-800 shadow-lg hidden md:flex flex-col">

        <div class="p-6 border-b dark:border-zinc-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Owner Panel
            </h2>
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <a href="{{ route('owner-dashboard.index') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700">
                📊 Dashboard
            </a>

            <a href="{{ route('dishes.index') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700">
                🍽️ Platos
            </a>

            <a href="{{ route('drinks.index') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700">
                🥤 Bebidas
            </a>

            <a href="{{ route('review.index') }}"
               class="block px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700">
                ⭐ Reviews
            </a>

        </nav>

        <div class="p-4 border-t dark:border-zinc-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 text-red-600">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </aside>

    {{-- Contenido --}}
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

</div>

@livewireScripts
</body>
</html>

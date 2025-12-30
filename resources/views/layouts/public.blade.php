<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sushi Buffet - @yield('title', 'Menú')</title>
</head>
<body class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans">

    <header class="bg-black text-white">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/sushi-logo.png') }}" class="h-10 w-10 rounded-full" alt="Logo Sushi Buffet">
                <div>
                    <h1 class="text-lg font-bold">
                        @yield('header-title', config('app.name', 'Sushi Buffet'))
                    </h1>
                    <p class="text-xs text-gray-300">
                        Escanea el QR para pedir directamente desde la mesa
                    </p>
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>



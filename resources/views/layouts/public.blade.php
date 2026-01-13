<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#6366f1">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>Sushi Buffet - @yield('title', 'Menú')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans">

    <header class="bg-gradient-to-r from-black to-gray-900 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="{{ asset('images/sushi-logo.png') }}" class="h-12 w-12 rounded-full border-2 border-white shadow-md" alt="Logo Sushi Buffet" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22white%22%3E%3Cpath d=%22M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z%22/%3E%3C/svg%3E'">
                </div>
                <div>
                    <h1 class="text-xl font-bold">
                        @yield('header-title', config('app.name', 'Sushi Buffet'))
                    </h1>
                    <p class="text-xs text-gray-300 mt-0.5">
                        Escanea el QR para pedir directamente desde la mesa
                    </p>
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen pb-20">
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>



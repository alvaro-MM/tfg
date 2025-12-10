<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" href="{{ asset('images/sushi-logo.png') }}" type="image/png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sushi Buffet - @yield('title')</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireScripts
</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans">
<!-- Navbar -->
<x-nav-bar/>

<!-- Contenido -->
<main class="min-h-screen">
    @yield('content')
</main>

<!-- Footer -->
<x-footer/>
</body>
</html>

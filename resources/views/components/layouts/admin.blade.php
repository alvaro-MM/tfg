<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="flex bg-gray-100 dark:bg-stone-900 min-h-screen">

    <aside class="w-64 bg-white dark:bg-stone-800 shadow-lg p-6">
        <h2 class="text-2xl font-bold text-stone-900 dark:text-stone-100">
            Panel de control
        </h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Administración
        </p>

        <ul class="mt-6 space-y-2 text-sm text-gray-700 dark:text-gray-300">
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-stone-700">
                    Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('tables.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-stone-700">
                    Mesas
                </a>
            </li>
            <li>
                <a href="{{ route('dishes.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-stone-700">
                    Platos
                </a>
            </li>
            <li>
                <a href="{{ route('drinks.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-stone-700">
                    Bebidas
                </a>
            </li>
            <li>
                <a href="{{ route('review.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-stone-700">
                    Reseñas
                </a>
            </li>
        </ul>
    </aside>

    <main class="flex-1 p-8">
        {{ $slot }}
    </main>

</body>
</html>

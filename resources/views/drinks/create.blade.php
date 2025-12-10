<x-layouts.app title="Nueva bebida">
    <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
        Crear bebida
    </h1>

    <form
        method="POST"
        action="{{ route('drinks.store') }}"
        enctype="multipart/form-data"
        class="mt-6 space-y-4">
        @csrf

        @include('drinks._form')

        <div class="flex gap-3">
            <button class="rounded bg-green-600 px-4 py-2 text-white">
                Crear
            </button>
            <a href="{{ route('drinks.index') }}" class="text-sm text-gray-600 dark:text-stone-300">
                Cancelar
            </a>
        </div>
    </form>
</x-layouts.app>
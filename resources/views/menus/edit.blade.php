<x-layouts.app :title="__('Editar Menú')">

    <div class="max-w-4xl">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100 mb-6">
            Editar menú
        </h1>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <form action="{{ route('menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                @include('menus._form')

                <div class="mt-6 flex gap-2">
                    <button type="submit"
                            class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        Actualizar
                    </button>
                    <a href="{{ route('menus.index') }}"
                       class="rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>

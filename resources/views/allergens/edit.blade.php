<x-layouts.admin :title="__('Editar alérgeno')">
    <div class="py-8">
        <div class="mx-auto max-w-3xl">

            <div class="rounded-xl bg-white p-6 shadow-md dark:bg-neutral-800">

                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-stone-100">
                        Editar alérgeno
                    </h1>

                    <a href="{{ route('allergens.index') }}"
                        class="text-sm text-stone-600 hover:text-stone-900 dark:text-stone-400 dark:hover:text-stone-200">
                        ← Volver
                    </a>
                </div>

                @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-800 dark:bg-red-900 dark:text-red-200">
                    <p class="mb-2 font-semibold">Hay errores en el formulario:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('allergens.update', $allergen) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @method('PUT')
                    @csrf

                    @include('allergens._form', [
                    'buttonText' => 'Actualizar alérgeno'
                    ])
                </form>

            </div>
        </div>
    </div>
</x-layouts.admin>
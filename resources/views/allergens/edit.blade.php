<x-layouts.app :title="__('Editar alérgeno')">
    <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar alérgeno</h1>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded bg-red-50 p-3 text-red-800 dark:bg-red-900 dark:text-red-200">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('allergens.update', $allergen) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @method('PUT')
        @csrf

        @include('allergens._form', ['buttonText' => 'Actualizar'])
    </form>
</x-layouts.app>

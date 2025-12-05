<x-layouts.app :title="__('Editar Reseña')">
    <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">Editar Reseña</h1>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('success') }}</div>
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

    <form action="{{ route('review.update', $review ) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        @method('PUT')

        @include('reviews._form')

        <div>
            <button type="submit" class="rounded bg-green-600 px-4 py-2 text-gray-600 dark:text-white">Actualizar</button>
            <a href="{{ route('review.index') }}" class="ml-2 text-sm text-gray-600 dark:text-stone-300">Cancelar</a>
        </div>
    </form>
</x-layouts.app>

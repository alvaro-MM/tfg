<x-layouts.app title="Detalle bebida">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">
            {{ $drink->name }}
        </h1>
        <div>
            <a href="{{ route('drinks.edit', $drink) }}"
               class="rounded bg-indigo-600 px-3 py-1 text-white">
                Editar
            </a>
            <a href="{{ route('drinks.index') }}"
               class="ml-2 text-sm text-gray-600 dark:text-stone-300">
                Volver
            </a>
        </div>
    </div>

    <div class="mt-6 space-y-3 text-stone-900 dark:text-stone-100">
        <p><strong>Descripción:</strong> {{ $drink->description }}</p>
        <p><strong>Precio:</strong> {{ number_format($drink->price, 2) }}</p>
        <p><strong>Disponible:</strong> {{ $drink->available ? 'Sí' : 'No' }}</p>

        @if($drink->image)
            <div>
                <img src="{{ asset('storage/'.$drink->image) }}" class="h-40 rounded-md">
            </div>
        @endif

        <div>
            <strong>Alérgenos:</strong>
            @if($drink->allergens->isEmpty())
                -
            @else
                <ul class="list-disc pl-6">
                    @foreach($drink->allergens as $a)
                        <li>{{ $a->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</x-layouts.app>

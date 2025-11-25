<x-layouts.app :title="__('Detalle plato')">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-stone-900 dark:text-stone-100">{{ $dish->name }}</h1>
        <div>
            <a href="{{ route('dishes.edit', $dish) }}" class="rounded bg-indigo-600 px-3 py-1 text-white">Editar</a>
            <a href="{{ route('dishes.index') }}" class="ml-2 text-sm text-gray-600 dark:text-stone-300">Volver</a>
        </div>
    </div>

    <div class="mt-4 space-y-2 text-stone-900 dark:text-stone-100">
        <div><strong>Descripción:</strong> {!! nl2br(e($dish->description)) !!}</div>
        <div><strong>Ingredientes:</strong> {!! nl2br(e($dish->ingredients)) ?: '-' !!}</div>
        <div><strong>Precio:</strong> {{ number_format($dish->price, 2) }}</div>
        <div><strong>Disponible:</strong> {{ $dish->available ? 'Sí' : 'No' }}</div>
        <div><strong>Especial:</strong> {{ $dish->special ? 'Sí' : 'No' }}</div>
        <div><strong>Imagen:</strong> @if($dish->image) <a href="{{ asset('storage/'.$dish->image) }}" target="_blank" class="text-blue-600">Ver imagen</a> @else - @endif</div>
        <div><strong>Categoría:</strong> {{ $dish->category->name ?? '-' }}</div>
        <div><strong>Alérgenos:</strong>
            @if($dish->allergens->isEmpty())
                -
            @else
                <ul class="list-disc pl-6">
                    @foreach($dish->allergens as $allergen)
                        <li>{{ $allergen->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>

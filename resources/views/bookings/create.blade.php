@extends('layout.tfg')

@section('content')
<div class="container mx-auto p-6 max-w-2xl bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Nueva Reserva</h1>

    @if($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-6 border border-red-300">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block mb-2 font-medium text-gray-700">
                Nombre del Cliente
            </label>
            <input type="text" name="name" id="name" class="border rounded-lg p-3 w-full focus:ring-2 focus:ring-indigo-500" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="table_id" class="block mb-2 font-medium text-gray-700">
                Mesa
            </label>
            <select name="table_id" id="table_id" class="border rounded-lg p-3 w-full focus:ring-2 focus:ring-indigo-500" required>
                @foreach($tables as $table)
                <option value="{{ $table->id }}" @selected(old('table_id')==$table->id)>
                    {{ $table->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="booking_date" class="block mb-2 font-medium text-gray-700">
                Fecha
            </label>
            <input type="date" name="booking_date" id="booking_date" class="border rounded-lg p-3 w-full focus:ring-2 focus:ring-indigo-500" value="{{ old('booking_date') }}" required>
        </div>

        <div>
            <label for="booking_time" class="block mb-2 font-medium text-gray-700">
                Hora de la reserva
            </label>
            <select name="booking_time" id="booking_time" class="border rounded-lg p-3 w-full focus:ring-2 focus:ring-indigo-500" required>
                <optgroup label="Comida (12:00 - 16:00)">
                    @for ($h = 12; $h <= 15; $h++)
                        @for ($m=0; $m < 60; $m +=15)
                        @if($h==15 && $m> 0) @break @endif
                        @php
                        $time = sprintf('%02d:%02d:00', $h, $m);
                        @endphp
                        <option value="{{ $time }}" @selected(old('booking_time')===$time)>
                            {{ substr($time, 0, 5) }}
                        </option>
                        @endfor
                        @endfor
                </optgroup>

                <optgroup label="Cena (19:00 - 23:00)">
                    @for ($h = 19; $h <= 22; $h++)
                        @for ($m=0; $m < 60; $m +=15)
                        @if($h==22 && $m> 0) @break @endif
                        @php
                        $time = sprintf('%02d:%02d:00', $h, $m);
                        @endphp
                        <option value="{{ $time }}" @selected(old('booking_time')===$time)>
                            {{ substr($time, 0, 5) }}
                        </option>
                        @endfor
                        @endfor
                </optgroup>
            </select>
            <p class="mt-2 text-sm text-gray-500">Duración de la reserva: <strong>90 minutos</strong></p>
        </div>

        <div>
            <label for="description" class="block mb-2 font-medium text-gray-700">
                Notas / Comentarios
            </label>
            <textarea name="description" id="description" rows="4" class="border rounded-lg p-3 w-full focus:ring-2 focus:ring-indigo-500" placeholder="Opcional">{{ old('description') }}</textarea>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Crear Reserva
            </button>
        </div>
    </form>
</div>
@endsection
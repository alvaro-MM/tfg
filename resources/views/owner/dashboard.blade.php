<x-layouts.owner title="Dashboard">
    <div class="max-w-7xl mx-auto py-10 space-y-12">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-extrabold tracking-tight
                       text-gray-900 dark:text-white">
                Dashboard del Owner
            </h1>

            <span class="px-4 py-2 rounded-full text-sm font-medium
                         bg-gradient-to-r from-indigo-500 to-purple-600
                         text-white shadow-md">
                Panel de Control
            </span>
        </div>

        {{-- RESUMEN --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-card title="Platos" :value="$dishesCount" icon="🍽️"/>
            <x-card title="Bebidas" :value="$drinksCount" icon="🥤"/>
            <x-card title="Reviews" :value="$reviewsCount" icon="📝"/>
            <x-card title="Rating medio" :value="$averageRating . ' ⭐'" icon="⭐"/>
        </div>

        {{-- ÚLTIMAS REVIEWS --}}
        <div class="bg-white/80 dark:bg-zinc-900/70 backdrop-blur
                    rounded-2xl shadow-lg p-8 transition hover:shadow-xl">

            <h2 class="text-2xl font-bold mb-6">Últimas reviews</h2>

            <div class="space-y-5">
                @foreach($latestReviews as $review)
                    <div class="flex justify-between items-center
                                p-4 rounded-xl hover:bg-gray-50
                                dark:hover:bg-zinc-800
                                transition-all duration-200">

                        <div>
                            <p class="font-semibold text-lg">
                                {{ $review->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $review->user->name }} ·
                                {{ $review->dish?->name ?? $review->drink?->name }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                     bg-yellow-100 text-yellow-700">
                            ⭐ {{ $review->rating }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TOP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <x-top-list title="Top Platos" :items="$topDishes"/>
            <x-top-list title="Top Bebidas" :items="$topDrinks"/>
        </div>

        {{-- GESTIÓN STAFF --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-8">Gestión de Camareros</h2>

            <div class="grid md:grid-cols-2 gap-10">

                {{-- Staff actuales --}}
                <div>
                    <h3 class="font-semibold mb-4 text-lg">Camareros activos</h3>

                    <div class="space-y-3">
                        @foreach($staffUsers as $user)
                            <div class="flex justify-between items-center
                                        p-3 rounded-xl bg-gray-50
                                        dark:bg-zinc-800 hover:scale-[1.02]
                                        transition">

                                <span>{{ $user->name }}</span>

                                <form method="POST" action="{{ route('owner.remove-staff', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-sm rounded-lg
                                                   bg-red-100 text-red-600
                                                   hover:bg-red-200 transition">
                                        Dar de baja
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Añadir staff --}}
                <div>
                    <h3 class="font-semibold mb-4 text-lg">Asignar nuevo staff</h3>

                    <div class="space-y-3 max-h-72 overflow-y-auto pr-2">
                        @foreach($usersWithoutStaff as $user)
                            <div class="flex justify-between items-center
                                        p-3 rounded-xl bg-gray-50
                                        dark:bg-zinc-800 hover:scale-[1.02]
                                        transition">

                                <span>{{ $user->name }}</span>

                                <form method="POST" action="{{ route('owner.make-staff', $user) }}">
                                    @csrf
                                    <button class="px-3 py-1 text-sm rounded-lg
                                                   bg-green-100 text-green-600
                                                   hover:bg-green-200 transition">
                                        Hacer staff
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- GESTIÓN MESAS --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-8">Gestión de Mesas</h2>

            {{-- Crear mesa --}}
            <form method="POST"
                  action="{{ route('owner.tables.store') }}"
                  class="grid md:grid-cols-5 gap-4 mb-10">
                @csrf

                <input type="text" name="name" placeholder="Nombre"
                       class="rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500">

                <input type="number" name="capacity" min="1"
                       placeholder="Capacidad"
                       class="rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500">

                <select name="status"
                        class="rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="available">Disponible</option>
                    <option value="occupied">Ocupada</option>
                    <option value="reserved">Reservada</option>
                </select>

                <input type="text" name="notes"
                       placeholder="Notas"
                       class="rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500">

                <button type="submit"
                        class="rounded-xl bg-gradient-to-r
                               from-indigo-500 to-purple-600
                               text-white font-semibold
                               hover:scale-105 transition shadow-md">
                    Crear
                </button>
            </form>

            {{-- Listado mesas --}}
            <div class="space-y-4">
                @foreach($tables as $table)

                    @php
                        $statusColors = [
                            'available' => 'bg-green-100 text-green-700',
                            'occupied' => 'bg-red-100 text-red-700',
                            'reserved' => 'bg-yellow-100 text-yellow-700',
                        ];
                    @endphp

                    <div class="flex justify-between items-center
                                p-4 rounded-2xl bg-gray-50
                                dark:bg-zinc-800 hover:shadow-md
                                transition-all">

                        <div>
                            <p class="font-semibold text-lg">
                                {{ $table->name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $table->capacity }} personas
                            </p>
                        </div>

                        <div class="flex items-center gap-4">

                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $statusColors[$table->status] }}">
                                {{ ucfirst($table->status) }}
                            </span>

                            <form method="POST"
                                  action="{{ route('owner.tables.destroy', $table) }}">
                                @csrf
                                @method('DELETE')

                                <button class="text-sm px-3 py-1 rounded-lg
                                               bg-red-100 text-red-600
                                               hover:bg-red-200 transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</x-layouts.owner>

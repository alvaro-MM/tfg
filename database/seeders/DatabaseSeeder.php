<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles y usuarios con roles (super-admin, admin, client)
        $this->call([
            RolesSeeder::class,
            UsersWithRolesSeeder::class,
        ]);

        // Usuarios base adicionales
        User::factory(5)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Orden recomendado para respetar FKs sin ciclos
        $this->call([
            CategorySeeder::class,
            AllergenSeeder::class,
            TableSeeder::class,
            MenuSeeder::class, // crea ofertas asociadas
            OfferSeeder::class,
            DrinkSeeder::class,
            DishSeeder::class,
            OrderSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}

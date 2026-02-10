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

    $year = now()->year;

        // Crear roles y usuarios con roles
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
            DrinkSeeder::class,
            DishSeeder::class,
            MenuSeeder::class, // crea ofertas asociadas y asocia platos
            OfferSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            UserSeeder::class,
        ]);
    }
}

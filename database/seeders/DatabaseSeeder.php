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
        // 1) Roles y usuarios con roles (idempotente)
        $this->call([
            RolesSeeder::class,
            UsersWithRolesSeeder::class,
        ]);

        // 2) Usuarios base adicionales (no duplicar el usuario de prueba)
        User::factory(5)->create();
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // 3) Entidades básicas necesarias para relaciones
        // Orden pensado para respetar claves foráneas y dependencias
        $this->call([
            CategorySeeder::class,
            AllergenSeeder::class,
            TableSeeder::class,
        ]);

        // 4) Productos y menús (bebidas, platos, menús y ofertas)
        $this->call([
            DrinkSeeder::class,
            DishSeeder::class,
            MenuSeeder::class, // asocia platos y crea ofertas
            OfferSeeder::class,
        ]);

        // 5) Operaciones posteriores (pedidos, facturas, reservas, reseñas)
        $this->call([
            OrderSeeder::class,
            InvoiceSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
        ]);

        // 6) Seeder final de utilidades / migraciones de datos sueltos
        $this->call([
            UserSeeder::class,
        ]);
    }
}

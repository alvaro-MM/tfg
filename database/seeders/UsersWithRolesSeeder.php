<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersWithRolesSeeder extends Seeder
{
    public function run(): void
    {

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Cliente
        $client = User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Cliente',
                'password' => bcrypt('password'),
            ]
        );
        $client->assignRole('client');

        // Dueño
        $client = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Dueño',
                'password' => bcrypt('password'),
            ]
        );
        $client->assignRole('owner');

        // Trabajador
        $client = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Trabajador',
                'password' => bcrypt('password'),
            ]
        );
        $client->assignRole('staff');
    }
}

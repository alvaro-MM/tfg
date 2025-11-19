<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersWithRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $super = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $super->assignRole('super-admin');

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
    }
}

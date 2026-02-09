<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {

            $date = Carbon::today()->subDays($daysAgo);
            $usersPerDay = rand(6, 10);

            for ($i = 0; $i < $usersPerDay; $i++) {
                $users[] = [
                    'name' => 'User ' . Str::random(5),
                    'email' => Str::random(10) . '@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
        }

        DB::table('users')->insert($users);
    }
}

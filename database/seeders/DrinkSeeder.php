<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Drink;
use App\Models\Allergen;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Drink::factory(10)->create()->each(function ($drink) {

            $allergens = Allergen::inRandomOrder()
                ->take(rand(0, 3))
                ->pluck('id');

            $drink->allergens()->sync($allergens);
        });
    }
}

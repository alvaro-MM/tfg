<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Allergen;

class AllergenSeeder extends Seeder
{
    public function run(): void
    {
        $allergens = [
            [
                'name' => 'Cereales con gluten',
                'image' => 'images/allergens/gluten.jpg',
            ],
            [
                'name' => 'Crustáceos',
                'image' => 'images/allergens/crustaceos.jpg',
            ],
            [
                'name' => 'Huevos',
                'image' => 'images/allergens/huevos.jpg',
            ],
            [
                'name' => 'Pescado',
                'image' => 'images/allergens/pescado.jpg',
            ],
            [
                'name' => 'Cacahuetes',
                'image' => 'images/allergens/cacahuetes.jpg',
            ],
            [
                'name' => 'Soja',
                'image' => 'images/allergens/soja.jpg',
            ],
            [
                'name' => 'Leche',
                'image' => 'images/allergens/leche.jpg',
            ],
            [
                'name' => 'Frutos de cáscara',
                'image' => 'images/allergens/frutos_cascara.jpg',
            ],
            [
                'name' => 'Apio',
                'image' => 'images/allergens/apio.jpg',
            ],
            [
                'name' => 'Mostaza',
                'image' => 'images/allergens/mostaza.jpg',
            ],
            [
                'name' => 'Sésamo',
                'image' => 'images/allergens/sesamo.jpg',
            ],
            [
                'name' => 'Sulfitos',
                'image' => 'images/allergens/sulfitos.jpg',
            ],
            [
                'name' => 'Altramuces',
                'image' => 'images/allergens/altramuces.jpg',
            ],
            [
                'name' => 'Moluscos',
                'image' => 'images/allergens/moluscos.jpg',
            ],
        ];

        foreach ($allergens as $data) {
            Allergen::create([
                'name' => $data['name'],
                'slug' => str($data['name'])->slug(),
                'description' => fake()->sentence(12),
                'image' => $data['image'],
            ]);
        }
    }
}

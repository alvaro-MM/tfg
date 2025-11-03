<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\allergen>
 */
class AllergenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $name = $this->faker->unique()->word();
        
        return [
            'description' => $this->faker->sentence(12),
            'image' => $this->faker->imageUrl(640, 480, 'food', true),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Reseña ' . $this->faker->unique()->numberBetween(1, 10000);
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->paragraph(),
            'user_id' => \App\Models\User::factory(),
            'dish_id' => \App\Models\Dish::factory(),
            'drink_id' => \App\Models\drink::factory(),
        ];
    }
}

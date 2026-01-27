<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 5, 25),
            'available' => $this->faker->boolean(90),
            'special' => $this->faker->boolean(20),
            'image' => $this->faker->imageUrl(800, 600, 'food', true),
            'category_id' => \App\Models\category::query()->inRandomOrder()->value('id') ?? \App\Models\category::factory(),
        ];
    }
}

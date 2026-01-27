<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\drink>
 */
class DrinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 2, 8),
            'available' => $this->faker->boolean(80),
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
        ];
    }
}

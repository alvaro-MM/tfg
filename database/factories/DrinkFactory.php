<?php

namespace Database\Factories;

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
            'category_id' => \App\Models\category::query()->inRandomOrder()->value('id') ?? \App\Models\category::factory(),
        ];
    }
}

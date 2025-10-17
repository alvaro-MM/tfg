<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Mesa ' . $this->faker->unique()->numberBetween(1, 50);
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(8),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Pedido ' . $this->faker->unique()->numberBetween(1000, 9999);
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(12),
            'table_id' => \App\Models\table::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}

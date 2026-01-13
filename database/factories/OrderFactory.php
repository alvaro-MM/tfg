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
        return [
            'user_id' => \App\Models\User::factory(),
            'table_id' => \App\Models\Table::factory(),
            'invoice_id' => null,
            'type' => $this->faker->randomElement(['buffet', 'a_la_carta']),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Reserva ' . $this->faker->unique()->numberBetween(1, 5000);
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(10),
            'table_id' => \App\Models\table::factory(),
            'user_id' => \App\Models\User::factory(),
            'offer_id' => \App\Models\offer::factory(),
        ];
    }
}

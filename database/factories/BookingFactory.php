<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
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
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'table_id' => \App\Models\Table::factory(),
            'user_id'  => \App\Models\User::factory(),
            'offer_id' => null,
            'booking_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),

            'booking_time' => $this->faker->randomElement([
                '12:00:00',
                '12:30:00',
                '13:00:00',
                '13:30:00',
                '14:00:00',
                '14:30:00',
                '15:00:00',
                '19:00:00',
                '19:30:00',
                '20:00:00',
                '20:30:00',
                '21:00:00',
                '21:30:00',
                '22:00:00',
            ]),

            'status' => 'active',
        ];
    }
}

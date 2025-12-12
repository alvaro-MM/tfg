<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
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
            'name' => 'Mesa ' . $this->faker->unique()->bothify('??-##'),
            'capacity' => $this->faker->numberBetween(1, 12),
            'status' => $this->faker->randomElement(['available', 'occupied', 'reserved']),
            'notes' => $this->faker->optional()->sentence(),

            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'menu_id' => Menu::inRandomOrder()->value('id') ?? Menu::factory(),
        ];
    }
}

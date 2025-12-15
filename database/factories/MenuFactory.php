<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Menú ' . ucfirst($this->faker->word()),
            'type' => $this->faker->randomElement(['daily', 'special', 'seasonal', 'themed']),
            'price' => $this->faker->randomFloat(2, 15, 50),
        ];
    }

    /**
     * Create a daily menu
     */
    public function daily(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'daily',
        ]);
    }

    /**
     * Create a special menu
     */
    public function special(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'special',
        ]);
    }

    /**
     * Create a seasonal menu
     */
    public function seasonal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'seasonal',
        ]);
    }

    /**
     * Create a themed menu
     */
    public function themed(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'themed',
        ]);
    }

    /**
     * Create menu with offers
     */
    public function withOffers(int $count = 2): static
    {
        return $this->afterCreating(function (\App\Models\Menu $menu) use ($count) {
            \App\Models\Offer::factory()->count($count)->create([
                'menu_id' => $menu->id,
            ]);
        });
    }
}

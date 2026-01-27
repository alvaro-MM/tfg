<?php

namespace Database\Factories;

use App\Models\Allergen;
use App\Models\Offer;
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
            'price' => $this->faker->randomFloat(2, 10, 30),
        ];
    }

    /**
     * Indicate que el menú tenga varias ofertas asociadas una vez creado.
     */
    public function withOffers(int $count = 2): static
    {
        return $this->afterCreating(function (\App\Models\Menu $menu) use ($count) {
            Offer::factory()->count($count)->create([
                'menu_id' => $menu->id,
            ]);
        });
    }
}

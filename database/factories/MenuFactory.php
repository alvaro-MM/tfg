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
            'allergen_id' => Allergen::query()->inRandomOrder()->value('id') ?? Allergen::factory(),
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

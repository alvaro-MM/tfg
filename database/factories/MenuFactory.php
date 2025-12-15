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
        $name = 'Menú ' . ucfirst($this->faker->word());
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(15),
            'allergen_id' => \App\Models\allergen::query()->inRandomOrder()->value('id') ?? \App\Models\allergen::factory(),
        ];
    }

    /**
     * Indicate que el menú tenga varias ofertas asociadas una vez creado.
     */
    public function withOffers(int $count = 2): static
    {
        return $this->afterCreating(function (\App\Models\Menu $menu) use ($count) {
            \App\Models\offer::factory()->count($count)->create([
                'menu_id' => $menu->id,
            ]);
        });
    }
}

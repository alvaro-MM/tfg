<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Oferta ' . ucfirst($this->faker->unique()->word());
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->sentence(12),
            'menu_id' => \App\Models\Menu::factory(),
            'discount' => $this->faker->numberBetween(5, 50),
        ];
    }
}

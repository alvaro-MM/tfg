<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $name = 'Reseña ' . $this->faker->unique()->numberBetween(1, 10000);

        $isDishReview = $this->faker->boolean(60); // 60% platos, 40% bebidas
        $hasImage = $this->faker->boolean(50); // 50% tendrán imagen

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),

            'user_id' => User::factory(),

            'dish_id' => $isDishReview ? Dish::inRandomOrder()->first()?->id : null,
            'drink_id' => ! $isDishReview ? Drink::inRandomOrder()->first()?->id : null,

            'image' => $hasImage
                ? 'reviews/review.jpg'
                : null,
        ];
    }
}

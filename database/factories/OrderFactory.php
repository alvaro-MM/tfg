<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Order $order) {
            // Reload table with menu relation
            $table = \App\Models\Table::with('menu')->find($order->table_id);
            $menu = $table?->menu;

            if ($menu && $menu->id) {
                // Get IDs of dishes in this menu directly from dish_menu table
                $dishIds = \DB::table('dish_menu')
                    ->where('menu_id', $menu->id)
                    ->pluck('dish_id')
                    ->toArray();

                if (!empty($dishIds)) {
                    // Get dishes using the IDs
                    $dishes = \App\Models\Dish::whereIn('id', $dishIds)
                        ->inRandomOrder()
                        ->limit(rand(2, min(5, count($dishIds))))
                        ->get();

                    foreach ($dishes as $dish) {
                        $order->dishes()->attach($dish->id, [
                            'quantity' => rand(1, 3),
                        ]);
                    }
                } else {
                    // If menu has no dishes, get random ones
                    $dishes = \App\Models\Dish::query()
                        ->inRandomOrder()
                        ->limit(rand(2, 5))
                        ->get();

                    foreach ($dishes as $dish) {
                        $order->dishes()->attach($dish->id, [
                            'quantity' => rand(1, 3),
                        ]);
                    }
                }
            } else {
                // If no menu, use random dishes
                $dishes = \App\Models\Dish::query()
                    ->inRandomOrder()
                    ->limit(rand(2, 5))
                    ->get();

                foreach ($dishes as $dish) {
                    $order->dishes()->attach($dish->id, [
                        'quantity' => rand(1, 3),
                    ]);
                }
            }

            // Add some drinks
            $drinks = \App\Models\Drink::query()
                ->inRandomOrder()
                ->limit(rand(1, 3))
                ->get();

            foreach ($drinks as $drink) {
                $order->drinks()->attach($drink->id, [
                    'quantity' => rand(1, 2),
                ]);
            }
        });
    }
}

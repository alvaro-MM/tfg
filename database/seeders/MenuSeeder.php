<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Dish;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::factory()->count(5)->create()->each(function ($menu) {
            // Get random dishes to attach to this menu
            $dishes = Dish::query()
                ->inRandomOrder()
                ->limit(rand(8, 15))
                ->get();

            // Attach dishes to menu with pricing info
            foreach ($dishes as $dish) {
                // 20% chance of being a special dish with custom price
                $isSpecial = rand(1, 100) <= 20;
                $customPrice = $isSpecial ? rand(20, 40) : null;

                $menu->dishes()->attach($dish->id, [
                    'is_special' => $isSpecial,
                    'custom_price' => $customPrice,
                ]);
            }

            // Create offers for the menu
            \App\Models\Offer::factory()->count(2)->create([
                'menu_id' => $menu->id,
            ]);
        });
        
        // Assign menus to tables that don't have one
        $menus = Menu::all();
        if (!$menus->isEmpty()) {
            \App\Models\Table::whereNull('menu_id')->each(function ($table) use ($menus) {
                $table->update([
                    'menu_id' => $menus->random()->id
                ]);
            });
        }
    }
}

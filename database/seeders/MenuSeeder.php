<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Dish;
use App\Models\Table;
use App\Models\Offer;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos todos los platos creados
        $allDishes = Dish::all();

        if ($allDishes->isEmpty()) {
            return;
        }

        // Reservamos un conjunto de platos que NUNCA se asociarán a menús
        // para probar correctamente la lógica de "fuera de menú"
        $outOfMenuDishIds = $allDishes
            ->random(min(10, $allDishes->count()))
            ->pluck('id')
            ->toArray();

        Menu::factory()->count(5)->create()->each(function (Menu $menu) use ($outOfMenuDishIds) {
            // Platos candidatos para este menú (excluyendo los "fuera de menú" globales)
            $menuDishes = Dish::query()
                ->whereNotIn('id', $outOfMenuDishIds)
                ->inRandomOrder()
                ->limit(rand(12, 20)) // más platos por menú
                ->get();

            // Adjuntamos platos al menú con info de pricing coherente con la lógica de buffet
            foreach ($menuDishes as $dish) {
                // 25% de probabilidad de ser un plato especial con precio custom (extra de menú)
                $isSpecial = rand(1, 100) <= 25;

                // Si es especial, fijamos un precio custom alrededor del precio del plato
                $customPrice = null;
                if ($isSpecial) {
                    $base = (float) $dish->price;
                    // Entre +20% y +80% del precio base
                    $factor = rand(120, 180) / 100;
                    $customPrice = round($base * $factor, 2);
                }

                $menu->dishes()->attach($dish->id, [
                    'is_special' => $isSpecial,
                    'custom_price' => $customPrice,
                ]);
            }

            // Crear un par de ofertas asociadas al menú
            Offer::factory()->count(2)->create([
                'menu_id' => $menu->id,
            ]);
        });

        // Asignar menús a mesas que todavía no tengan uno
        $menus = Menu::all();
        if ($menus->isNotEmpty()) {
            Table::whereNull('menu_id')->each(function (Table $table) use ($menus) {
                $table->update([
                    'menu_id' => $menus->random()->id,
                ]);
            });
        }
    }
}

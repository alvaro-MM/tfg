<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\Menu;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tables without menu first
        $tables = Table::factory()->count(15)->create();
        
        // Assign menus after MenuSeeder has run
        // Note: This runs after MenuSeeder in DatabaseSeeder due to proper ordering
    }
    
    public function assignMenus(): void
    {
        $menus = Menu::all();
        if ($menus->isEmpty()) {
            return;
        }
        
        Table::whereNull('menu_id')->each(function ($table) use ($menus) {
            $table->update([
                'menu_id' => $menus->random()->id
            ]);
        });
    }
}

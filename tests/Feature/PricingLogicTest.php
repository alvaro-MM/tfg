<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Menu;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Category;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PricingLogicTest extends TestCase
{
    use RefreshDatabase;

    protected Menu $menu;
    protected Category $category;
    protected Table $table;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = User::factory()->create();

        // Create category
        $this->category = Category::create([
            'name' => 'Platos Principales',
            'slug' => 'platos-principales',
        ]);

        // Create menu with price 15.00
        $this->menu = Menu::create([
            'name' => 'Menú del Día',
            'type' => 'daily',
            'price' => 15.00,
        ]);

        // Create table
        $this->table = Table::factory()->create([
            'menu_id' => $this->menu->id,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test that a normal dish (not special) uses menu price
     */
    public function test_normal_dish_uses_menu_price(): void
    {
        // Create a normal dish with its own price
        $dish = Dish::create([
            'name' => 'Pollo',
            'slug' => 'pollo',
            'description' => 'Pollo a la plancha',
            'price' => 8.00, // Dish price (should be ignored)
            'available' => true,
            'special' => false,
            'category_id' => $this->category->id,
        ]);

        // Attach to menu as normal (not special)
        $this->menu->dishes()->attach($dish->id, [
            'is_special' => false,
            'custom_price' => null,
        ]);

        // Get price through menu
        $price = $this->menu->getDishPrice($dish->id);

        // Should use menu price, not dish price
        $this->assertEquals(15.00, $price);
    }

    /**
     * Test that a special dish uses custom price
     */
    public function test_special_dish_uses_custom_price(): void
    {
        // Create a special dish
        $specialDish = Dish::create([
            'name' => 'Filete de Res Premium',
            'slug' => 'filete-res-premium',
            'description' => 'Filete de res premium',
            'price' => 20.00,
            'available' => true,
            'special' => true,
            'category_id' => $this->category->id,
        ]);

        // Attach to menu as special with custom price
        $this->menu->dishes()->attach($specialDish->id, [
            'is_special' => true,
            'custom_price' => 25.00,
        ]);

        // Get price through menu
        $price = $this->menu->getDishPrice($specialDish->id);

        // Should use custom price from pivot table
        $this->assertEquals(25.00, $price);
    }

    /**
     * Test order total calculation with mixed prices
     */
    public function test_order_total_with_mixed_prices(): void
    {
        // Create normal dish
        $normalDish = Dish::create([
            'name' => 'Arroz',
            'slug' => 'arroz',
            'description' => 'Arroz blanco',
            'price' => 5.00,
            'available' => true,
            'special' => false,
            'category_id' => $this->category->id,
        ]);

        // Create special dish
        $specialDish = Dish::create([
            'name' => 'Camarones al Ajillo',
            'slug' => 'camarones-ajillo',
            'description' => 'Camarones al ajillo',
            'price' => 18.00,
            'available' => true,
            'special' => true,
            'category_id' => $this->category->id,
        ]);

        // Create drink
        $drink = Drink::create([
            'name' => 'Coca Cola',
            'slug' => 'coca-cola',
            'description' => '350ml',
            'price' => 2.50,
            'available' => true,
            'category_id' => $this->category->id,
        ]);

        // Attach dishes to menu
        $this->menu->dishes()->attach($normalDish->id, [
            'is_special' => false,
            'custom_price' => null,
        ]);

        $this->menu->dishes()->attach($specialDish->id, [
            'is_special' => true,
            'custom_price' => 20.00,
        ]);

        // Create order
        $order = Order::create([
            'user_id' => $this->user->id,
            'table_id' => $this->table->id,
            'type' => 'buffet',
            'date' => now(),
        ]);

        // Add items to order
        // 2x normal dish (2 x 15.00 = 30.00)
        $order->dishes()->attach($normalDish->id, ['quantity' => 2]);
        // 1x special dish (1 x 20.00 = 20.00)
        $order->dishes()->attach($specialDish->id, ['quantity' => 1]);
        // 2x drink (2 x 2.50 = 5.00)
        $order->drinks()->attach($drink->id, ['quantity' => 2]);

        // Calculate total: 30 + 20 + 5 = 55.00
        $total = $order->calculateTotal($this->menu);

        $this->assertEquals(55.00, $total);
    }

    /**
     * Test that dishes without menu relationship use their own price
     */
    public function test_dishes_without_menu_use_own_price(): void
    {
        // Create a standalone dish
        $standaloneDish = Dish::create([
            'name' => 'Pizza',
            'slug' => 'pizza',
            'description' => 'Pizza margherita',
            'price' => 12.00,
            'available' => true,
            'special' => false,
            'category_id' => $this->category->id,
        ]);

        // Create order without menu context
        $order = Order::create([
            'user_id' => $this->user->id,
            'table_id' => $this->table->id,
            'type' => 'buffet',
            'date' => now(),
        ]);

        $order->dishes()->attach($standaloneDish->id, ['quantity' => 1]);

        // Calculate total without menu (should use dish price)
        $total = $order->calculateTotal(null);

        $this->assertEquals(12.00, $total);
    }

    /**
     * Test special dish without custom_price still uses menu price
     */
    public function test_special_dish_without_custom_price_uses_menu_price(): void
    {
        $dish = Dish::create([
            'name' => 'Pasta',
            'slug' => 'pasta',
            'description' => 'Pasta a la carbonara',
            'price' => 10.00,
            'available' => true,
            'special' => true,
            'category_id' => $this->category->id,
        ]);

        // Attach as special but without custom_price
        $this->menu->dishes()->attach($dish->id, [
            'is_special' => true,
            'custom_price' => null,
        ]);

        $price = $this->menu->getDishPrice($dish->id);

        // Should fall back to menu price
        $this->assertEquals(15.00, $price);
    }

    /**
     * Test rounding of totals
     */
    public function test_total_rounding(): void
    {
        $dish = Dish::create([
            'name' => 'Hamburguesa',
            'slug' => 'hamburguesa',
            'description' => 'Hamburguesa clásica',
            'price' => 5.00,
            'available' => true,
            'special' => false,
            'category_id' => $this->category->id,
        ]);

        $this->menu->dishes()->attach($dish->id, [
            'is_special' => false,
            'custom_price' => null,
        ]);

        $order = Order::create([
            'user_id' => $this->user->id,
            'table_id' => $this->table->id,
            'type' => 'buffet',
            'date' => now(),
        ]);

        // 3x dish at 15.00 each = 45.00
        $order->dishes()->attach($dish->id, ['quantity' => 3]);

        $total = $order->calculateTotal($this->menu);

        // Should be properly rounded to 2 decimals
        $this->assertEquals(45.00, $total);
        $this->assertIsFloat($total);
    }
}

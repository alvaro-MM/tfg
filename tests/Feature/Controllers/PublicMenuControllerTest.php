<?php

use App\Models\Table;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Menu;

test('public menu page is displayed for a valid token', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
    ]);

    $response = $this->get(route('public.menu', 'token123'));

    $response->assertStatus(200);
    $response->assertViewIs('public.menu');
    $response->assertViewHas('table');
});

test('public menu page returns 404 for invalid token', function () {
    $response = $this->get(route('public.menu', 'invalid-token'));

    $response->assertStatus(404);
});

test('menu data returns global dishes and drinks when table has no menu', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'menu_id' => null,
    ]);

    $category = Category::factory()->create();

    $dish = Dish::factory()->create([
        'available' => true,
        'category_id' => $category->id,
    ]);

    $drink = Drink::factory()->create([
        'available' => true,
        'category_id' => $category->id,
    ]);

    $response = $this->getJson(route('public.menu.data', 'token123'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'table' => ['id', 'name', 'capacity'],
            'categories',
            'dishes',
            'drinks',
        ]);

    $response->assertJsonFragment([
        'id' => $dish->id,
        'name' => $dish->name,
    ]);

    $response->assertJsonFragment([
        'id' => $drink->id,
        'name' => $drink->name,
    ]);
});

test('menu data returns menu dishes and drinks when table has a menu', function () {
    $menu = Menu::factory()->create();

    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'menu_id' => $menu->id,
    ]);

    $category = Category::factory()->create();

    $dish = Dish::factory()->create([
        'available' => true,
        'category_id' => $category->id,
    ]);

    $menu->dishes()->attach($dish->id);

    $drink = Drink::factory()->create([
        'available' => true,
        'category_id' => $category->id,
    ]);

    $response = $this->getJson(route('public.menu.data', 'token123'));

    $response->assertStatus(200);

    $response->assertJsonFragment([
        'id' => $dish->id,
        'name' => $dish->name,
    ]);

    $response->assertJsonFragment([
        'id' => $drink->id,
        'name' => $drink->name,
    ]);
});

test('menu data returns 404 for invalid token', function () {
    $response = $this->getJson(route('public.menu.data', 'invalid-token'));

    $response->assertStatus(404);
});

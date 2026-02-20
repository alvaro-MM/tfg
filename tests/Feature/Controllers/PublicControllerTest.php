<?php

use App\Models\Category;
use App\Models\Menu;
use App\Models\Dish;
use App\Models\Allergen;

test('public dishes page is displayed', function () {
    $response = $this->get(route('dishes.public'));

    $response->assertStatus(200);
    $response->assertViewIs('public.dishes');
});

test('public prices page shows menus with dishes', function () {

    $menu = Menu::factory()->create();
    $dish = Dish::factory()->create();

    $menu->dishes()->attach($dish);

    $response = $this->get(route('prices'));

    $response->assertStatus(200);
    $response->assertViewIs('public.prices');

    $response->assertViewHas(
        'menus',
        fn($menus) =>
        $menus->count() === 1 &&
            $menus->first()->relationLoaded('dishes')
    );
});

test('public about page is displayed', function () {

    $response = $this->get(route('about'));

    $response->assertStatus(200);
    $response->assertViewIs('public.about');
});

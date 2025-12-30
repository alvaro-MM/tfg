<?php

use App\Models\Menu;
use App\Models\Offer;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear rol si no existe
    if (!Role::where('name', 'admin')->exists()) {
        Role::create(['name' => 'admin']);
    }

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    $this->offer = Offer::factory()->create();
    $this->dish = Dish::factory()->create();
});

it('can view menus index', function () {
    Menu::factory()->count(5)->create();

    $response = $this->get(route('menus.index'));

    $response->assertStatus(200);
    $response->assertViewHas('menus');
});

it('can view create menu page', function () {
    $response = $this->get(route('menus.create'));

    $response->assertStatus(200);
    $response->assertViewHasAll(['offers', 'dishes']);
});

it('can store a new menu', function () {
    $data = [
        'name' => 'Menú Test',
        'type' => 'daily',
        'price' => 20.50,
        'dish_ids' => [$this->dish->id],
    ];

    $response = $this->post(route('menus.store'), $data);

    $response->assertRedirect(route('menus.index'));
    $this->assertDatabaseHas('menus', ['name' => 'Menú Test']);
    $this->assertDatabaseHas('dish_menu', ['dish_id' => $this->dish->id]);
});

it('can show a menu', function () {
    $menu = Menu::factory()->create();

    $response = $this->get(route('menus.show', $menu));

    $response->assertStatus(200);
    $response->assertViewHas('menu');
});

it('can view edit menu page', function () {
    $menu = Menu::factory()->create();

    $response = $this->get(route('menus.edit', $menu));

    $response->assertStatus(200);
    $response->assertViewHasAll(['menu', 'offers', 'dishes']);
});

it('can update a menu', function () {
    $menu = Menu::factory()->create();

    $data = [
        'name' => 'Menú Actualizado',
        'type' => 'special',
        'price' => 30,
        'dish_ids' => [$this->dish->id],
    ];

    $response = $this->put(route('menus.update', $menu), $data);

    $response->assertRedirect(route('menus.index'));
    $this->assertDatabaseHas('menus', ['name' => 'Menú Actualizado']);
});

it('can delete a menu without tables assigned', function () {
    $menu = Menu::factory()->create();

    $response = $this->delete(route('menus.destroy', $menu));

    $response->assertRedirect(route('menus.index'));
    $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
});

it('cannot delete a menu with tables assigned', function () {
    $menu = Menu::factory()->create();
    $menu->tables()->create(['name' => 'Mesa 1']);

    $response = $this->delete(route('menus.destroy', $menu));

    $response->assertRedirect(route('menus.index'));
    $response->assertSessionHas('error', 'No se puede eliminar el menú porque está asignado a mesas.');
    $this->assertDatabaseHas('menus', ['id' => $menu->id]);
});

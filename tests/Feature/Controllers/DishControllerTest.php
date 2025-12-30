<?php

use App\Models\Dish;
use App\Models\Category;
use App\Models\Allergen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear rol admin si no existe
    if (!Role::where('name', 'admin')->exists()) {
        Role::create(['name' => 'admin']);
    }

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    $this->category = Category::factory()->create();
    $this->allergen = Allergen::factory()->create();
});

it('can view dishes index', function () {
    Dish::factory()->count(5)->create([
        'category_id' => $this->category->id,
        'allergen_id' => $this->allergen->id,
    ]);

    $response = $this->get(route('dishes.index'));

    $response->assertStatus(200);
    $response->assertViewHas('dishes');
});

it('can view create dish page', function () {
    $response = $this->get(route('dishes.create'));

    $response->assertStatus(200);
    $response->assertViewHasAll(['categories', 'allergens']);
});

it('can show a dish', function () {
    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
        'allergen_id' => $this->allergen->id,
    ]);

    $response = $this->get(route('dishes.show', $dish));

    $response->assertStatus(200);
    $response->assertViewHas('dish');
});

it('can view edit dish page', function () {
    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
        'allergen_id' => $this->allergen->id,
    ]);

    $response = $this->get(route('dishes.edit', $dish));

    $response->assertStatus(200);
    $response->assertViewHasAll(['dish', 'categories', 'allergens']);
});

it('can update a dish', function () {
    Storage::fake('public');

    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
        'allergen_id' => $this->allergen->id,
    ]);

    $file = UploadedFile::fake()->image('updated.jpg');

    $data = [
        'name' => 'Plato Actualizado',
        'description' => 'Nueva descripción',
        'ingredients' => 'Ingrediente X, Ingrediente Y',
        'price' => 15.00,
        'category_id' => $this->category->id,
        'allergen_ids' => [$this->allergen->id],
        'image' => $file,
        'available' => true,
        'special' => false,
    ];

    $response = $this->put(route('dishes.update', $dish), $data);

    $response->assertRedirect(route('dishes.show', $dish));
    $this->assertDatabaseHas('dishes', ['name' => 'Plato Actualizado']);
    Storage::disk('public')->assertExists(Dish::first()->image);
});

it('can delete a dish', function () {
    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
        'allergen_id' => $this->allergen->id,
    ]);

    $response = $this->delete(route('dishes.destroy', $dish));

    $response->assertRedirect(route('dishes.index'));
    $this->assertDatabaseMissing('dishes', ['id' => $dish->id]);
    $this->assertDatabaseMissing('dish_allergen', ['dish_id' => $dish->id]);
});

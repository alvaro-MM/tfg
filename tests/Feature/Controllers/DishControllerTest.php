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
    $dishes = Dish::factory()->count(5)->create([
        'category_id' => $this->category->id,
    ]);

    foreach ($dishes as $dish) {
        $dish->allergens()->sync([$this->allergen->id]);
    }

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
    ]);

    $dish->allergens()->sync([$this->allergen->id]);

    $response = $this->get(route('dishes.show', $dish));

    $response->assertStatus(200);
    $response->assertViewHas('dish');
});

it('can view edit dish page', function () {
    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $dish->allergens()->sync([$this->allergen->id]);

    $response = $this->get(route('dishes.edit', $dish));

    $response->assertStatus(200);
    $response->assertViewHasAll(['dish', 'categories', 'allergens']);
});

it('can update a dish', function () {
    Storage::fake('public');

    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $dish->allergens()->sync([$this->allergen->id]);

    $file = UploadedFile::fake()->image('updated.jpg');

    $data = [
        'name' => 'Plato actualizado',
        'description' => 'Nueva descripcion',
        'ingredients' => 'Ingredientes varios',
        'price' => 15.00,
        'category_id' => $this->category->id,
        'allergen_ids' => [$this->allergen->id],
        'image' => $file,
        'available' => true,
        'special' => false,
    ];

    $response = $this->put(route('dishes.update', $dish), $data);

    $response->assertRedirect(route('dishes.show', $dish));
    $this->assertDatabaseHas('dishes', ['name' => 'Plato actualizado']);
    Storage::disk('public')->assertExists(Dish::first()->image);
});

it('can delete a dish', function () {
    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $dish->allergens()->sync([$this->allergen->id]);

    $response = $this->delete(route('dishes.destroy', $dish));

    $response->assertRedirect(route('dishes.index'));
    $this->assertDatabaseMissing('dishes', ['id' => $dish->id]);
    $this->assertDatabaseMissing('dish_allergen', ['dish_id' => $dish->id]);
});

it('stores a dish without image and sets defaults', function () {

    $data = [
        'name' => 'Plato sin imagen',
        'description' => 'Descripcion',
        'ingredients' => 'Ingredientes',
        'price' => 9.99,
        'category_id' => $this->category->id,
    ];

    $response = $this->post(route('dishes.store'), $data);

    $response->assertRedirect(route('dishes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('dishes', [
        'name' => 'Plato sin imagen',
        'slug' => 'plato-sin-imagen',
        'available' => false,
        'special' => false,
    ]);
});

it('stores a dish using provided slug', function () {

    $data = [
        'name' => 'Plato con slug',
        'slug' => 'mi-slug-personalizado',
        'description' => 'Descripcion',
        'ingredients' => 'Ingredientes',
        'price' => 11.50,
        'category_id' => $this->category->id,
    ];

    $this->post(route('dishes.store'), $data);

    $this->assertDatabaseHas('dishes', [
        'slug' => 'mi-slug-personalizado',
    ]);
});

it('updates a dish without changing image', function () {

    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
        'image' => 'dishes/original.jpg',
    ]);

    $data = [
        'name' => 'Plato sin nueva imagen',
        'description' => 'Descripcion',
        'ingredients' => 'Ingredientes',
        'price' => 10,
        'category_id' => $this->category->id,
        'available' => true,
    ];

    $this->put(route('dishes.update', $dish), $data);

    $this->assertDatabaseHas('dishes', [
        'id' => $dish->id,
        'name' => 'Plato sin nueva imagen',
        'image' => 'dishes/original.jpg',
    ]);
});

it('removes all allergens when allergen_ids is empty on update', function () {

    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $dish->allergens()->sync([$this->allergen->id]);
    expect($dish->allergens)->toHaveCount(1);

    $data = [
        'name' => 'Plato actualizado',
        'description' => 'Descripcion',
        'ingredients' => 'Ingredientes',
        'price' => 12,
        'category_id' => $this->category->id,
        'allergen_ids' => [],
    ];

    $this->put(route('dishes.update', $dish), $data);

    $dish->refresh();
    expect($dish->allergens)->toHaveCount(0);
});

it('detaches allergens before deleting dish', function () {

    $dish = Dish::factory()->create([
        'category_id' => $this->category->id,
    ]);

    $dish->allergens()->sync([$this->allergen->id]);

    $this->delete(route('dishes.destroy', $dish));

    $this->assertDatabaseMissing('dish_allergen', [
        'dish_id' => $dish->id,
    ]);
});

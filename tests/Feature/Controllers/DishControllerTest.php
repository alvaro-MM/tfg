<?php

use App\Models\Dish;
use App\Models\Category;
use App\Models\Allergen;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->category = Category::factory()->create();
    $this->allergen = Allergen::factory()->create();
});

test('authenticated users can visit the dishes index', function () {
    $response = $this->get(route('dishes.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the create dish page', function () {
    $response = $this->get(route('dishes.create'));
    $response->assertStatus(200);
});

test('authenticated users can view a dish', function () {
    $dish = Dish::factory()->create(['category_id' => $this->category->id]);
    $response = $this->get(route('dishes.show', $dish));
    $response->assertStatus(200);
});

test('authenticated users can visit the edit dish page', function () {
    $dish = Dish::factory()->create(['category_id' => $this->category->id]);
    $response = $this->get(route('dishes.edit', $dish));
    $response->assertStatus(200);
});

test('authenticated users can update a dish', function () {
    Storage::fake('public');

    $dish = Dish::factory()->create(['category_id' => $this->category->id]);

    $data = [
        'name' => 'Lasagna',
        'description' => 'Delicious layered pasta',
        'ingredients' => 'Pasta, Cheese, Meat',
        'price' => 9.50,
        'category_id' => $this->category->id,
        'allergen_ids' => [$this->allergen->id],
        'available' => true,
        'special' => true,
        'image' => UploadedFile::fake()->image('lasagna.jpg'),
    ];

    $response = $this->put(route('dishes.update', $dish), $data);
    $response->assertRedirect(route('dishes.show', $dish));

    $this->assertDatabaseHas('dishes', ['name' => 'Lasagna']);
});

test('authenticated users can delete a dish', function () {
    $dish = Dish::factory()->create(['category_id' => $this->category->id]);
    $response = $this->delete(route('dishes.destroy', $dish));
    $response->assertRedirect(route('dishes.index'));

    $this->assertDatabaseMissing('dishes', ['id' => $dish->id]);
});

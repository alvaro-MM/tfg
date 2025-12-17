<?php

use App\Models\Drink;
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

test('authenticated users can visit the drinks index', function () {
    $response = $this->get(route('drinks.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the create drink page', function () {
    $response = $this->get(route('drinks.create'));
    $response->assertStatus(200);
});

test('authenticated users can store a new drink', function () {
    Storage::fake('public');

    $data = [
        'name' => 'Coca Cola',
        'description' => 'Refresco de cola',
        'price' => 1.50,
        'category_id' => $this->category->id,
        'available' => true,
        'allergen_ids' => [$this->allergen->id],
        'image' => UploadedFile::fake()->image('drink.jpg'),
    ];

    $response = $this->post(route('drinks.store'), $data);
    $response->assertRedirect(route('drinks.index'));

    $this->assertDatabaseHas('drinks', ['name' => 'Coca Cola']);
});

test('authenticated users can view a drink', function () {
    $drink = Drink::factory()->create(['category_id' => $this->category->id]);
    $response = $this->get(route('drinks.show', $drink));
    $response->assertStatus(200);
});

test('authenticated users can visit the edit page', function () {
    $drink = Drink::factory()->create(['category_id' => $this->category->id]);
    $response = $this->get(route('drinks.edit', $drink));
    $response->assertStatus(200);
});

test('authenticated users can update a drink', function () {
    Storage::fake('public');

    $drink = Drink::factory()->create(['category_id' => $this->category->id]);

    $data = [
        'name' => 'Pepsi',
        'description' => 'Refresco de cola Pepsi',
        'price' => 2.00,
        'category_id' => $this->category->id,
        'available' => false,
        'allergen_ids' => [$this->allergen->id],
        'image' => UploadedFile::fake()->image('pepsi.jpg'),
    ];

    $response = $this->put(route('drinks.update', $drink), $data);
    $response->assertRedirect(route('drinks.show', $drink));

    $this->assertDatabaseHas('drinks', ['name' => 'Pepsi']);
});

test('authenticated users can delete a drink', function () {
    $drink = Drink::factory()->create(['category_id' => $this->category->id]);
    $response = $this->delete(route('drinks.destroy', $drink));
    $response->assertRedirect(route('drinks.index'));

    $this->assertDatabaseMissing('drinks', ['id' => $drink->id]);
});

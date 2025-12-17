<?php

use App\Models\User;
use App\Models\Category;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('authenticated users can view the categories index', function () {
    $response = $this->get(route('categories.index'));

    $response->assertStatus(200);
    $response->assertViewIs('categories.index');
    $response->assertViewHas('categories');
});

test('authenticated users can access the create category form', function () {
    $response = $this->get(route('categories.create'));

    $response->assertStatus(200);
    $response->assertViewIs('categories.create');
    $response->assertViewHas('categories');
});

test('authenticated users can store a new category', function () {
    $data = [
        'name' => 'Postres',
        'description' => 'Categoría de postres y dulces',
        'parent_id' => null,
    ];

    $response = $this->post(route('categories.store'), $data);

    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Postres',
        'description' => 'Categoría de postres y dulces',
        'slug' => 'postres',
    ]);
});

test('authenticated users can view a category', function () {
    $category = Category::factory()->create([
        'name' => 'Entrantes',
        'description' => 'Platos para empezar',
        'slug' => 'entrantes',
        'parent_id' => null,
    ]);

    $response = $this->get(route('categories.show', $category));

    $response->assertStatus(200);
    $response->assertViewIs('categories.show');
    $response->assertViewHas('category');
});

test('authenticated users can access the edit category form', function () {
    $category = Category::factory()->create([
        'name' => 'Bebidas',
        'description' => 'Bebidas frías y calientes',
        'slug' => 'bebidas',
        'parent_id' => null,
    ]);

    $response = $this->get(route('categories.edit', $category));

    $response->assertStatus(200);
    $response->assertViewIs('categories.edit');
    $response->assertViewHas('category');
    $response->assertViewHas('categories');
});

test('authenticated users can update a category', function () {
    $category = Category::factory()->create([
        'name' => 'Old name',
        'description' => 'Old description',
        'slug' => 'old-name',
        'parent_id' => null,
    ]);

    $data = [
        'name' => 'New name',
        'description' => 'New description',
        'parent_id' => null,
    ];

    $response = $this->put(route('categories.update', $category), $data);

    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New name',
        'description' => 'New description',
        'slug' => 'new-name',
    ]);
});

test('authenticated users can delete a category', function () {
    $category = Category::factory()->create([
        'name' => 'Temporal',
        'description' => 'Categoría temporal',
        'slug' => 'temporal',
        'parent_id' => null,
    ]);

    $response = $this->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

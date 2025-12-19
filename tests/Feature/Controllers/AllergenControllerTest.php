<?php

use App\Models\User;
use App\Models\Allergen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('authenticated users can view the allergens index', function () {
    $response = $this->get(route('allergens.index'));

    $response->assertStatus(200);
    $response->assertViewIs('allergens.index');
});

test('authenticated users can access the create allergen form', function () {
    $response = $this->get(route('allergens.create'));

    $response->assertStatus(200);
    $response->assertViewIs('allergens.create');
});

test('authenticated users can store a new allergen', function () {
    Storage::fake('public');

    $data = [
        'name' => 'Gluten',
        'description' => 'Contiene proteínas presentes en cereales.',
        'image' => UploadedFile::fake()->image('gluten.png'),
    ];

    $response = $this->post(route('allergens.store'), $data);

    $response->assertRedirect(route('allergens.index'));

    $this->assertDatabaseHas('allergens', [
        'name' => 'Gluten',
        'description' => 'Contiene proteínas presentes en cereales.',
    ]);
});

test('authenticated users can access the edit allergen form', function () {
    $allergen = Allergen::factory()->create([
        'name' => 'Lactosa',
        'description' => 'Azúcar presente en la leche',
    ]);

    $response = $this->get(route('allergens.edit', $allergen));

    $response->assertStatus(200);
    $response->assertViewIs('allergens.edit');
    $response->assertViewHas('allergen');
});

test('authenticated users can update an allergen', function () {
    Storage::fake('public');

    $allergen = Allergen::factory()->create([
        'name' => 'Old name',
        'description' => 'Old description',
    ]);

    $data = [
        'name' => 'Updated name',
        'description' => 'Updated description',
        'image' => UploadedFile::fake()->image('updated.png'),
    ];

    $response = $this->put(route('allergens.update', $allergen), $data);

    $response->assertRedirect(route('allergens.index'));

    $this->assertDatabaseHas('allergens', [
        'id' => $allergen->id,
        'name' => 'Updated name',
        'description' => 'Updated description',
    ]);
});

test('authenticated users can delete an allergen', function () {
    Storage::fake('public');

    $allergen = Allergen::factory()->create([
        'name' => 'Test allergen',
        'description' => 'Test description',
        'image' => '/storage/allergens/test.png',
    ]);

    Storage::disk('public')->put('allergens/test.png', 'fake-content');

    $response = $this->delete(route('allergens.destroy', $allergen));

    $response->assertRedirect(route('allergens.index'));

    $this->assertDatabaseMissing('allergens', [
        'id' => $allergen->id,
    ]);

    Storage::disk('public')->assertMissing('allergens/test.png');
});

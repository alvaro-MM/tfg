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

<?php

use App\Models\Drink;
use App\Models\Category;
use App\Models\Allergen;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
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

test('authenticated users can delete a drink', function () {
    $drink = Drink::factory()->create(['category_id' => $this->category->id]);
    $response = $this->delete(route('drinks.destroy', $drink));
    $response->assertRedirect(route('drinks.index'));

    $this->assertDatabaseMissing('drinks', ['id' => $drink->id]);
});

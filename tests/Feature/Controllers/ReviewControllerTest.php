<?php

use App\Models\Review;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    $this->dish = Dish::factory()->create();
    $this->drink = Drink::factory()->create();
});

test('authenticated users can visit the reviews index', function () {
    $response = $this->get(route('review.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the create review page', function () {
    $response = $this->get(route('review.create'));
    $response->assertStatus(200);
});

test('authenticated users can view a review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ]);

    $response = $this->get(route('review.show', $review));
    $response->assertStatus(200);
});

test('authenticated users can visit the edit review page', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ]);

    $response = $this->get(route('review.edit', $review));
    $response->assertStatus(200);
});

test('authenticated users can delete a review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ]);

    $response = $this->delete(route('review.destroy', $review));
    $response->assertRedirect(route('review.index'));

    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
});

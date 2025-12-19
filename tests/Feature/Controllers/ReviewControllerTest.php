<?php

use App\Models\Review;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;

beforeEach(function () {
    $this->user = User::factory()->create();
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

test('authenticated users can store a new review', function () {
    $data = [
        'name' => 'Great review',
        'description' => 'This is a review description',
        'comment' => 'Amazing!',
        'rating' => 5,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ];

    $response = $this->post(route('review.store'), $data);
    $response->assertRedirect(route('review.index'));

    $this->assertDatabaseHas('reviews', ['name' => 'Great review']);
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

test('authenticated users can update a review', function () {
    $review = Review::factory()->create([
        'user_id' => $this->user->id,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ]);

    $data = [
        'name' => 'Updated Review',
        'description' => 'Updated description',
        'comment' => 'Even better!',
        'rating' => 4,
        'dish_id' => $this->dish->id,
        'drink_id' => $this->drink->id,
    ];

    $response = $this->put(route('review.update', $review), $data);
    $response->assertRedirect(route('review.index'));

    $this->assertDatabaseHas('reviews', ['name' => 'Updated Review']);
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

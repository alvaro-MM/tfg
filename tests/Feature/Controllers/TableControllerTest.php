<?php

use App\Models\Table;
use App\Models\User;
use App\Models\Menu;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->menu = Menu::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('authenticated users can visit the tables index', function () {
    $response = $this->get(route('tables.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the create table page', function () {
    $response = $this->get(route('tables.create'));
    $response->assertStatus(200);
});

test('authenticated users can store an available table without user', function () {
    $data = [
        'name' => 'Mesa 1',
        'capacity' => 4,
        'status' => 'available',
        'notes' => 'Cerca de la ventana',
        'menu_id' => $this->menu->id,
    ];

    $response = $this->post(route('tables.store'), $data);
    $response->assertRedirect(route('tables.index'));

    $this->assertDatabaseHas('tables', ['name' => 'Mesa 1', 'user_id' => null]);
});

test('storing occupied table without user fails', function () {
    $data = [
        'name' => 'Mesa 2',
        'capacity' => 2,
        'status' => 'occupied',
        'notes' => '',
        'menu_id' => $this->menu->id,
    ];

    $response = $this->post(route('tables.store'), $data);
    $response->assertSessionHasErrors('user_id');
});

test('authenticated users can store an occupied table with user', function () {
    $data = [
        'name' => 'Mesa 3',
        'capacity' => 2,
        'status' => 'occupied',
        'notes' => 'Mesa VIP',
        'menu_id' => $this->menu->id,
        'user_id' => $this->otherUser->id,
    ];

    $response = $this->post(route('tables.store'), $data);
    $response->assertRedirect(route('tables.index'));

    $this->assertDatabaseHas('tables', ['name' => 'Mesa 3', 'user_id' => $this->otherUser->id]);
});

test('authenticated users can view a table', function () {
    $table = Table::factory()->create();
    $response = $this->get(route('tables.show', $table));
    $response->assertStatus(200);
});

test('authenticated users can visit the edit table page', function () {
    $table = Table::factory()->create();
    $response = $this->get(route('tables.edit', $table));
    $response->assertStatus(200);
});

test('authenticated users can update a table', function () {
    $table = Table::factory()->create(['status' => 'available', 'user_id' => null]);

    $data = [
        'name' => 'Mesa 10',
        'capacity' => 6,
        'status' => 'occupied',
        'notes' => 'Actualizada',
        'menu_id' => $this->menu->id,
        'user_id' => $this->otherUser->id,
    ];

    $response = $this->put(route('tables.update', $table), $data);
    $response->assertRedirect(route('tables.index'));

    $this->assertDatabaseHas('tables', ['name' => 'Mesa 10', 'user_id' => $this->otherUser->id]);
});

test('authenticated users can delete a table', function () {
    $table = Table::factory()->create();
    $response = $this->delete(route('tables.destroy', $table));
    $response->assertRedirect(route('tables.index'));

    $this->assertDatabaseMissing('tables', ['id' => $table->id]);
});

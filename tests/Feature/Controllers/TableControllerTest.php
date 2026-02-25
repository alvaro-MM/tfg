<?php

use App\Models\Table;
use App\Models\User;
use App\Models\Menu;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
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

test('storing occupied table without user succeeds', function () {
    $data = [
        'name' => 'Mesa ocupada',
        'capacity' => 4,
        'status' => 'occupied',
        'menu_id' => $this->menu->id,
    ];

    $response = $this->post(route('tables.store'), $data);

    $response->assertRedirect(route('tables.index'));

    $this->assertDatabaseHas('tables', [
        'name' => 'Mesa ocupada',
        'status' => 'occupied',
        'user_id' => null,
    ]);
});

test('store forces user_id to null when status is available', function () {
    $data = [
        'name' => 'Mesa libre',
        'capacity' => 4,
        'status' => 'available',
        'menu_id' => $this->menu->id,
        'user_id' => $this->otherUser->id,
    ];

    $this->post(route('tables.store'), $data);

    $this->assertDatabaseHas('tables', [
        'name' => 'Mesa libre',
        'user_id' => null,
    ]);
});

test('store generates qr token if missing', function () {
    $data = [
        'name' => 'Mesa QR',
        'capacity' => 4,
        'status' => 'available',
        'menu_id' => $this->menu->id,
    ];

    $this->post(route('tables.store'), $data);

    $table = Table::where('name', 'Mesa QR')->first();

    expect($table->qr_token)->not->toBeNull();
});

test('updating table to reserved without user succeeds', function () {
    $table = Table::factory()->create(['status' => 'available']);

    $data = [
        'name' => $table->name,
        'capacity' => $table->capacity,
        'status' => 'reserved',
        'menu_id' => $this->menu->id,
    ];

    $response = $this->put(route('tables.update', $table), $data);

    $response->assertRedirect(route('tables.index'));

    $table->refresh();
    expect($table->status)->toBe('reserved');
});

test('update clears user_id when status is available', function () {
    $table = Table::factory()->create([
        'status' => 'occupied',
        'user_id' => $this->otherUser->id,
    ]);

    $data = [
        'name' => 'Mesa libre otra vez',
        'capacity' => 4,
        'status' => 'available',
        'menu_id' => $this->menu->id,
    ];

    $this->put(route('tables.update', $table), $data);

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'user_id' => null,
    ]);
});

test('update generates qr token if missing', function () {
    $table = Table::factory()->create([
        'qr_token' => null,
    ]);

    $data = [
        'name' => $table->name,
        'capacity' => $table->capacity,
        'status' => 'available',
        'menu_id' => $this->menu->id,
    ];

    $this->put(route('tables.update', $table), $data);

    $table->refresh();

    expect($table->qr_token)->not->toBeNull();
});

test('generate qr creates token when missing', function () {
    $table = Table::factory()->create(['qr_token' => null]);

    $response = $this->post(route('tables.generate-qr', $table));

    $response->assertRedirect(route('tables.show', $table));

    $table->refresh();
    expect($table->qr_token)->not->toBeNull();
});

test('generate qr does not regenerate token if already exists', function () {
    $table = Table::factory()->create(['qr_token' => 'existing-token']);

    $this->post(route('tables.generate-qr', $table));

    $table->refresh();

    expect($table->qr_token)->toBe('existing-token');
});

<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
  
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

    $this->user = User::factory()->create();
    $this->user->assignRole('staff');
    $this->actingAs($this->user);
});

test('index shows all tables with order count', function () {
    Table::factory()->count(3)->create();

    $response = $this->get(route('staff-tables.index'));

    $response->assertStatus(200);
    $response->assertViewIs('staff.tables.index');
    $response->assertViewHas('tables');
});

test('show sets the table with orders and relationships', function () {
    $table = Table::factory()->create();

    $response = $this->get(route('staff-tables.show', $table));

    $response->assertStatus(200);
    $response->assertViewIs('staff.tables.show');
    $response->assertViewHas('table');
});

test('occupy changes the status from available to busy', function () {
    $table = Table::factory()->create(['status' => 'available']);

    $response = $this->post(route('staff-tables.occupy', $table));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Mesa ocupada');

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'status' => 'occupied',
        'user_id' => $this->user->id,
    ]);
});

test('Occupy does not allow you to take a table that is not available', function () {
    $table = Table::factory()->create(['status' => 'reserved']);

    $response = $this->post(route('staff-tables.occupy', $table));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'La mesa no está disponible');
});

test('free up an occupied table', function () {
    $table = Table::factory()->create(['status' => 'occupied', 'user_id' => $this->user->id]);

    $response = $this->post(route('staff-tables.free', $table));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Mesa liberada');

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'status' => 'available',
        'user_id' => null,
    ]);
});

test('reserve an available table', function () {
    $table = Table::factory()->create(['status' => 'available']);

    $response = $this->post(route('staff-tables.reserve', $table));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Mesa reservada correctamente.');

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'status' => 'reserved',
    ]);
});

test('reserve does not allow booking an occupied table', function () {
    $table = Table::factory()->create(['status' => 'occupied']);

    $response = $this->post(route('staff-tables.reserve', $table));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'La mesa no está disponible.');
});

test('cancelReserve correctly cancels a reservation', function () {
    $table = Table::factory()->create(['status' => 'reserved']);

    $response = $this->post(route('staff-tables.cancel-reserve', $table));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Reserva cancelada.');

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'status' => 'available',
    ]);
});

test('cancelReserve does not cancel if the table was not reserved', function () {
    $table = Table::factory()->create(['status' => 'available']);

    $response = $this->post(route('staff-tables.cancel-reserve', $table));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'La mesa no está reservada.');
});

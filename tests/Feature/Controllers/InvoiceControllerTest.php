<?php

use App\Models\Invoice;
use App\Models\Table;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {

    Role::firstOrCreate(['name' => 'admin']);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');

    $this->actingAs($this->user);

    $this->table = Table::factory()->create();
});


test('authenticated users can visit the invoices index', function () {
    $response = $this->get(route('invoices.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the create invoice page', function () {
    $response = $this->get(route('invoices.create'));
    $response->assertStatus(200);
});

test('authenticated users can store a new invoice', function () {
    $data = [
        'table_id' => $this->table->id,
        'total' => 45.90,
        'date' => now()->toDateString(),
    ];

    $response = $this->post(route('invoices.store'), $data);

    $response->assertRedirect(route('invoices.index'));

    $this->assertDatabaseHas('invoices', [
        'table_id' => $this->table->id,
        'total' => 45.90,
    ]);
});

test('authenticated users can visit the edit invoice page', function () {
    $invoice = Invoice::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->get(route('invoices.edit', $invoice));
    $response->assertStatus(200);
});

test('authenticated users can update an invoice', function () {
    $invoice = Invoice::factory()->create([
        'table_id' => $this->table->id,
        'total' => 20.00,
    ]);

    $data = [
        'table_id' => $this->table->id,
        'total' => 60.50,
        'date' => now()->toDateString(),
    ];

    $response = $this->put(route('invoices.update', $invoice), $data);
    $response->assertRedirect(route('invoices.index'));

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'total' => 60.50,
    ]);
});

test('authenticated users can delete an invoice', function () {
    $invoice = Invoice::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->delete(route('invoices.destroy', $invoice));
    $response->assertRedirect(route('invoices.index'));

    $this->assertDatabaseMissing('invoices', [
        'id' => $invoice->id,
    ]);
});

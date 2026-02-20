<?php

use App\Models\Invoice;
use App\Models\Table;
use App\Models\User;
use App\Models\Order;
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

test('invoice index shows paginated invoices with table relation', function () {

    Invoice::factory()->count(3)->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->get(route('invoices.index'));

    $response->assertStatus(200);
    $response->assertViewIs('invoices.index');

    $response->assertViewHas('invoices', function ($invoices) {
        return $invoices->count() > 0 &&
            $invoices->first()->relationLoaded('table');
    });
});

test('invoice index filters by price range', function () {

    Invoice::factory()->create(['total' => 50, 'table_id' => $this->table->id]);
    Invoice::factory()->create(['total' => 300, 'table_id' => $this->table->id]);
    Invoice::factory()->create(['total' => 600, 'table_id' => $this->table->id]);

    $response = $this->get(route('invoices.index', [
        'price_range' => '100-500',
    ]));

    $response->assertStatus(200);

    $response->assertViewHas('invoices', function ($invoices) {
        return $invoices->every(
            fn($invoice) =>
            $invoice->total >= 100 && $invoice->total <= 500
        );
    });
});

test('invoice index filters invoices above 500', function () {

    Invoice::factory()->create(['total' => 200, 'table_id' => $this->table->id]);
    Invoice::factory()->create(['total' => 700, 'table_id' => $this->table->id]);

    $response = $this->get(route('invoices.index', [
        'price_range' => '500+',
    ]));

    $response->assertStatus(200);

    $response->assertViewHas('invoices', function ($invoices) {
        return $invoices->count() === 1 &&
            $invoices->first()->total >= 500;
    });
});

test('authenticated users can store an invoice', function () {

    $order = Order::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $data = [
        'table_id' => $this->table->id,
        'order_id' => $order->id,
        'total' => 120.75,
        'date' => now()->toDateString(),
    ];

    $response = $this->post(route('invoices.store'), $data);

    $response->assertRedirect(route('invoices.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('invoices', [
        'table_id' => $this->table->id,
        'order_id' => $order->id,
        'total' => 120.75,
    ]);
});

test('authenticated users can view an invoice with relations', function () {

    $invoice = Invoice::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->get(route('invoices.show', $invoice));

    $response->assertStatus(200);
    $response->assertViewIs('invoices.show');

    $response->assertViewHas('invoice', function ($inv) {
        return $inv->relationLoaded('table') &&
            $inv->relationLoaded('order');
    });
});

test('create invoice view receives tables and orders', function () {

    $response = $this->get(route('invoices.create'));

    $response->assertStatus(200);
    $response->assertViewHasAll(['tables', 'orders']);
});

test('edit invoice view receives invoice tables and orders', function () {

    $invoice = Invoice::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->get(route('invoices.edit', $invoice));

    $response->assertStatus(200);
    $response->assertViewHasAll(['invoice', 'tables', 'orders']);
});

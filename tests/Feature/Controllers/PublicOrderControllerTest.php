<?php

use App\Models\Table;
use App\Models\Order;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Menu;

test('send to kitchen fails if cart is empty', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
    ]);

    session(["cart_token123" => ['items' => []]]);

    $response = $this->post(route('public.order.send', 'token123'));

    $response->assertRedirect(route('public.menu', 'token123'));
    $response->assertSessionHas('error', 'El carrito está vacío');

    $this->assertDatabaseCount('orders', 0);
});

test('send to kitchen creates an order and attaches items', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'capacity' => 2,
    ]);

    $dish = Dish::factory()->create(['price' => 10]);
    $drink = Drink::factory()->create(['price' => 2]);

    session([
        "cart_token123" => [
            'items' => [
                [
                    'id' => $dish->id,
                    'type' => 'dish',
                    'price' => 10,
                    'quantity' => 2,
                ],
                [
                    'id' => $drink->id,
                    'type' => 'drink',
                    'price' => 2,
                    'quantity' => 1,
                ],
            ],
        ],
    ]);

    $response = $this->post(route('public.order.send', 'token123'));

    $response->assertRedirect();
    $this->assertDatabaseCount('orders', 1);

    $order = Order::first();
    expect($order->table_id)->toBe($table->id);

    $this->assertDatabaseHas('dish_order', [
        'order_id' => $order->id,
        'dish_id' => $dish->id,
        'quantity' => 2,
    ]);

    $this->assertDatabaseHas('drink_order', [
        'order_id' => $order->id,
        'drink_id' => $drink->id,
        'quantity' => 1,
    ]);
});

test('payment page redirects if there are no pending orders', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
    ]);

    $response = $this->get(route('public.payment', 'token123'));

    $response->assertRedirect(route('public.menu', 'token123'));
    $response->assertSessionHas('info');
});

test('payment page is displayed with pending orders', function () {
    $table = Table::factory()->hasMenu()->create([
        'qr_token' => 'token123',
    ]);

    Order::factory()->count(2)->create([
        'table_id' => $table->id,
        'invoice_id' => null,
    ]);

    $response = $this->get(route('public.payment', 'token123'));

    $response->assertStatus(200);
    $response->assertViewIs('public.payment');
    $response->assertViewHas(['table', 'orders', 'cartItems', 'total']);
});

test('checkout fails if there are no pending orders', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
    ]);

    $response = $this->post(route('public.checkout', 'token123'), [
        'customer_name' => 'Juan',
        'customer_email' => 'juan@test.com',
        'customer_phone' => '123456789',
        'payment_method' => 'cash',
        'accept_terms' => true,
    ]);

    $response->assertRedirect(route('public.menu', 'token123'));
    $response->assertSessionHas('error');
});

test('checkout creates invoice and assigns it to orders', function () {
    $table = Table::factory()->hasMenu()->create([
        'qr_token' => 'token123',
    ]);

    $orders = Order::factory()->count(2)->create([
        'table_id' => $table->id,
        'invoice_id' => null,
    ]);

    $response = $this->post(route('public.checkout', 'token123'), [
        'customer_name' => 'Juan',
        'customer_email' => 'juan@test.com',
        'customer_phone' => '123456789',
        'payment_method' => 'cash',
        'accept_terms' => true,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseCount('invoices', 1);

    foreach ($orders as $order) {
        $this->assertNotNull($order->fresh()->invoice_id);
    }
});

test('order confirmation page is shown', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
    ]);

    $order = Order::factory()->create([
        'table_id' => $table->id,
    ]);

    $response = $this->get(route('public.order.confirm', [
        'token' => 'token123',
        'orderId' => $order->id,
    ]));

    $response->assertStatus(200);
    $response->assertViewIs('public.confirm');
    $response->assertViewHas(['order', 'table']);
});

test('buffet status endpoint returns correct structure', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'capacity' => 4,
    ]);

    $response = $this->getJson(route('public.buffet.status', 'token123'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'table' => ['id', 'name', 'capacity'],
            'buffet_status' => ['limit', 'used', 'available', 'window_minutes'],
        ]);
});

test('send to kitchen returns json error if cart is empty', function () {
    $table = Table::factory()->create(['qr_token' => 'token123']);
    session(["cart_token123" => ['items' => []]]);

    $response = $this->postJson(route('public.order.send', 'token123'));

    $response->assertStatus(400)
        ->assertJson(['error' => 'El carrito está vacío']);
});

test('send to kitchen fails when buffet limit is exceeded', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'capacity' => 1,
    ]);

    Dish::factory()->create(['price' => 10]);

    session([
        "cart_token123" => [
            'items' => [
                ['id' => 1, 'type' => 'dish', 'price' => 10, 'quantity' => 50],
            ],
        ],
    ]);

    $response = $this->post(route('public.order.send', 'token123'));

    $response->assertRedirect(route('public.menu', 'token123'));
    $response->assertSessionHas('error');
});

test('send to kitchen returns json when buffet limit exceeded', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'capacity' => 1,
    ]);

    Dish::factory()->create();

    session([
        "cart_token123" => [
            'items' => [
                ['id' => 1, 'type' => 'dish', 'price' => 10, 'quantity' => 50],
            ],
        ],
    ]);

    $response = $this->postJson(route('public.order.send', 'token123'));

    $response->assertStatus(422)
        ->assertJsonStructure(['error', 'message', 'validation']);
});

test('send to kitchen returns json success', function () {
    $table = Table::factory()->create(['qr_token' => 'token123']);
    $dish = Dish::factory()->create(['price' => 10]);

    session([
        "cart_token123" => [
            'items' => [
                ['id' => $dish->id, 'type' => 'dish', 'price' => 10, 'quantity' => 1],
            ],
        ],
    ]);

    $response = $this->postJson(route('public.order.send', 'token123'));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('send to kitchen handles exception', function () {
    $table = Table::factory()->create(['qr_token' => 'token123']);

    session([
        "cart_token123" => [
            'items' => [
                [
                    'id' => 999999,
                    'type' => 'dish',
                    'price' => 10,
                    'quantity' => 1,
                ],
            ],
        ],
    ]);

    $response = $this->post(route('public.order.send', 'token123'));

    $response->assertRedirect(route('public.menu', 'token123'));
    $response->assertSessionHas('error');
});

test('payment charges drinks from second one onwards', function () {
    $table = Table::factory()->hasMenu()->create(['qr_token' => 'token123']);
    $drink = Drink::factory()->create(['price' => 3]);

    $order = Order::factory()->create(['table_id' => $table->id]);

    $order->drinks()->syncWithoutDetaching([
        $drink->id => ['quantity' => 2],
    ]);

    $response = $this->get(route('public.payment', 'token123'));

    $response->assertStatus(200);
    $response->assertViewHas('total');
});

test('payment adds extra price for special dishes', function () {
    $table = Table::factory()->create(['qr_token' => 'token123']);

    $menu = Menu::factory()->create();
    $table->menu()->associate($menu)->save();

    $dish = Dish::factory()->create(['price' => 10]);

    $menu->dishes()->attach($dish->id, [
        'is_special' => true,
        'custom_price' => 5,
    ]);

    $order = Order::factory()->create(['table_id' => $table->id]);

    $order->dishes()->syncWithoutDetaching([
        $dish->id => ['quantity' => 2],
    ]);

    $response = $this->get(route('public.payment', 'token123'));

    $response->assertStatus(200);
});

test('checkout works with json request', function () {
    $table = Table::factory()->hasMenu()->create(['qr_token' => 'token123']);
    Order::factory()->create(['table_id' => $table->id]);

    $response = $this->postJson(route('public.checkout', 'token123'));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('checkout validation fails without required fields', function () {
    $table = Table::factory()->create(['qr_token' => 'token123']);
    Order::factory()->create(['table_id' => $table->id]);

    $response = $this->post(route('public.checkout', 'token123'), []);

    $response->assertSessionHasErrors(['customer_name', 'customer_email']);
});

test('buffet status returns correct values', function () {
    $table = Table::factory()->create([
        'qr_token' => 'token123',
        'capacity' => 2,
    ]);

    $response = $this->getJson(route('public.buffet.status', 'token123'));

    $response->assertJsonPath('table.capacity', 2);
});

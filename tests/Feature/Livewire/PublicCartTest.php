<?php

use App\Livewire\PublicCart;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Table;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->user = User::factory()->create();
    $this->user->assignRole('admin');

    $this->table = Table::factory()->create(['user_id' => $this->user->id]);
    $this->dish = Dish::factory()->create(['available' => true]);
    $this->drink = Drink::factory()->create(['available' => true]);
});

it('can mount and load initial cart', function () {
    Livewire::test(PublicCart::class, ['token' => $this->table->qr_token])
        ->assertSet('token', $this->table->qr_token)
        ->assertSet('items', [])
        ->assertSet('count', 0)
        ->assertSet('total', 0.00);
});

it('can add dish and drink to cart', function () {
    Livewire::test(PublicCart::class, ['token' => $this->table->qr_token])
        ->call('addItem', $this->dish->id, 'dish', 2)
        ->call('addItem', $this->drink->id, 'drink', 1)
        ->assertSet('count', 3)
        ->assertSet('items', function ($items) {
            return count($items) === 2;
        });
});

it('removes item from cart', function () {
    $component = Livewire::test(PublicCart::class, ['token' => $this->table->qr_token]);

    $component->call('addItem', $this->dish->id, 'dish', 1);
    $component->call('removeItem', $this->dish->id, 'dish');

    $component->assertSet('count', 0)
        ->assertSet('items', []);
});

it('updates item quantity', function () {
    $component = Livewire::test(PublicCart::class, ['token' => $this->table->qr_token]);
    $component->call('addItem', $this->dish->id, 'dish', 1);
    $component->call('updateQuantity', $this->dish->id, 'dish', 3);
    $component->assertSet('count', 3);
});

it('clears the cart', function () {
    $component = Livewire::test(PublicCart::class, ['token' => $this->table->qr_token]);
    $component->call('addItem', $this->dish->id, 'dish', 1);
    $component->call('clearCart');
    $component->assertSet('items', [])
        ->assertSet('count', 0)
        ->assertSet('total', 0.00);
});

it('sendToKitchen creates an order', function () {
    $component = Livewire::test(PublicCart::class, ['token' => $this->table->qr_token]);
    $component->call('addItem', $this->dish->id, 'dish', 1);
    $component->call('sendToKitchen');

    $component->assertSet('items', [])
        ->assertSet('count', 0)
        ->assertSet('total', 0.00);

    $this->assertDatabaseHas('orders', ['table_id' => $this->table->id]);
});

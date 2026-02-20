<?php

use App\Livewire\PublicMenu;
use App\Models\Table;
use App\Models\User;
use App\Models\Dish;
use App\Models\Drink;
use App\Models\Category;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    $this->user = User::factory()->create();
    $this->user->assignRole('admin');

    $this->table = Table::factory()->create(['user_id' => $this->user->id]);

    $this->category1 = Category::factory()->create(['name' => 'Entrante']);
    $this->category2 = Category::factory()->create(['name' => 'Bebidas']);

    $this->dish1 = Dish::factory()->create([
        'name' => 'Sushi',
        'category_id' => $this->category1->id,
        'available' => true
    ]);

    $this->dish2 = Dish::factory()->create([
        'name' => 'Arroz',
        'category_id' => $this->category1->id,
        'available' => true
    ]);

    $this->drink1 = Drink::factory()->create([
        'name' => 'Agua',
        'category_id' => $this->category2->id,
        'available' => true
    ]);
});

it('mounts public menu component', function () {
    Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ])
        ->assertSet('selectedCategory', 'all')
        ->assertSet('table.id', $this->table->id);
});

it('loads menu data arrays', function () {
    $component = Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ]);

    expect($component->get('categories'))->toBeArray();
    expect($component->get('dishes'))->toBeArray();
    expect($component->get('drinks'))->toBeArray();
});

it('can call selectCategory without errors', function () {
    Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ])
        ->call('selectCategory')
        ->assertHasNoErrors();
});

it('handles missing product data safely', function () {
    Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ])
        ->call('addProductToCart')
        ->assertHasNoErrors();
});

it('adds product to cart without errors', function () {
    Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ])
        ->call('addProductToCart', $this->dish1->id, 'dish')
        ->assertHasNoErrors();
});

it('calculates available buffet slots', function () {
    $component = Livewire::test(PublicMenu::class, [
        'token' => $this->table->qr_token,
    ]);

    expect($component->get('availableSlots'))->toBeInt();
});

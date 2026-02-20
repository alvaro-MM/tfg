<?php

use App\Models\Table;
use App\Models\User;
use App\Models\Dish;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'staff']);

    $this->user = User::factory()->create();
    $this->user->assignRole('staff');

    $this->actingAs($this->user);
});


test('staff can view dashboard with statistics', function () {

    Table::factory()->count(3)->create(['status' => 'available']);
    Table::factory()->count(2)->create(['status' => 'occupied']);
    Table::factory()->count(1)->create(['status' => 'reserved']);

    Dish::factory()->count(2)->create(['available' => false]);

    $response = $this->get(route('staff-dashboard.index'));

    $response->assertStatus(200);
    $response->assertViewHas([
        'totalTables',
        'freeTables',
        'occupiedTables',
        'reservedTables',
        'outOfStockDishes',
    ]);
});

<?php

use App\Models\User;
use App\Models\Table;
use App\Models\Order;
use App\Models\Review;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {

    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->actingAs($this->admin);
});

test('admin dashboard index loads without errors', function () {

    User::factory()->count(3)->create();
    Table::factory()->count(2)->create();
    Order::factory()->count(2)->create();
    Review::factory()->count(1)->create();

    $response = $this->get(route('admin-dashboard.index'));

    $response->assertStatus(200);
    $response->assertViewHasAll([
        'usersToday',
        'latestUsers',
        'totalTables',
        'availableTables',
        'occupiedTables',
        'reservedTables',
        'tablesOccupationPercent',
        'ordersToday',
        'activeOrders',
        'latestOrders',
        'ordersPerHourLabels',
        'ordersPerHourData',
        'reviewsToday',
        'latestReviewsToday',
        'reviewingUsersToday',
        'topDishesToday',
        'topDrinksToday',
        'alerts'
    ]);
});

test('admin billing loads without errors', function () {

    Invoice::factory()->count(3)->create(['total' => 50]);

    $response = $this->get(route('admin.billing'));

    $response->assertStatus(200);
    $response->assertViewHasAll(['stats', 'chartLabels', 'chartData']);

    $stats = $response->viewData('stats');
    expect($stats['today'])->toBeNumeric();
});

test('admin performance load without errors', function () {
    User::factory()->count(3)->create();
    Order::factory()->count(5)->create();
    Review::factory()->count(2)->create();

    $response = $this->get(route('performance'));

    $response->assertStatus(200);
    $response->assertViewHasAll([
        'totalUsers',
        'totalOrders',
        'totalReviews',
        'chartLabels',
        'chartData',
        'ordersChartLabels',
        'ordersChartData'
    ]);

    expect($response->viewData('totalUsers'))->toBeNumeric();
    expect($response->viewData('totalOrders'))->toBeNumeric();
    expect($response->viewData('totalReviews'))->toBeNumeric();
});

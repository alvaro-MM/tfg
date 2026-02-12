<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

test('dailyPerformance downloads a PDF with the daily performance', function () {

    Carbon::setTestNow(Carbon::create(2025, 2, 10, 14, 0, 0));

    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    User::factory()->count(2)->create(['created_at' => now()]);
    Order::factory()->count(3)->create(['created_at' => now()]);
    Review::factory()->count(1)->create(['created_at' => now()]);

    User::factory()->create(['created_at' => now()->subDay()]);
    Order::factory()->create(['created_at' => now()->subDay()]);
    Review::factory()->create(['created_at' => now()->subDay()]);

    $response = $this->get(route('admin.pdf.daily-performance'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('dailyPerformance generates the PDF even if there is no data for the day', function () {

    Carbon::setTestNow(Carbon::create(2025, 2, 10));

    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $response = $this->get(route('admin.pdf.daily-performance'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('dailyPerformance works with orders at different times', function () {

    Carbon::setTestNow(Carbon::create(2025, 2, 10, 20, 0, 0));

    Role::create(['name' => 'admin']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    Order::factory()->create(['created_at' => now()->setHour(13)]);
    Order::factory()->create(['created_at' => now()->setHour(21)]);
    Order::factory()->create(['created_at' => now()->setHour(10)]);

    $response = $this->get(route('admin.pdf.daily-performance'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

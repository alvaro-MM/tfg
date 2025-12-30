<?php

use App\Models\Offer;
use App\Models\Menu;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
   
    if (!Role::where('name', 'admin')->exists()) {
        Role::create(['name' => 'admin']);
    }

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    $this->menu = Menu::factory()->create();
});


it('can view offers index', function () {
    Offer::factory()->count(5)->create(['menu_id' => $this->menu->id]);

    $response = $this->get(route('offers.index'));

    $response->assertStatus(200);
    $response->assertViewHas('offers');
});

it('can view create offer page', function () {
    $response = $this->get(route('offers.create'));

    $response->assertStatus(200);
    $response->assertViewHas('menus');
});

it('can store a new offer', function () {
    $data = [
        'name' => 'Black Friday',
        'slug' => 'black-friday',
        'description' => '50% off on all items',
        'discount' => 50,
        'menu_id' => $this->menu->id,
    ];

    $response = $this->post(route('offers.store'), $data);

    $response->assertRedirect(route('offers.index'));
    $this->assertDatabaseHas('offers', ['name' => 'Black Friday']);
});

it('can show an offer', function () {
    $offer = Offer::factory()->create(['menu_id' => $this->menu->id]);

    $response = $this->get(route('offers.show', $offer));

    $response->assertStatus(200);
    $response->assertViewHas('offer');
});

it('can view edit offer page', function () {
    $offer = Offer::factory()->create(['menu_id' => $this->menu->id]);

    $response = $this->get(route('offers.edit', $offer));

    $response->assertStatus(200);
    $response->assertViewHasAll(['offer', 'menus']);
});

it('can update an offer', function () {
    $offer = Offer::factory()->create(['menu_id' => $this->menu->id]);

    $data = [
        'name' => 'Updated Offer',
        'slug' => 'updated-offer',
        'description' => 'Updated description',
        'discount' => 30,
        'menu_id' => $this->menu->id,
    ];

    $response = $this->put(route('offers.update', $offer), $data);

    $response->assertRedirect(route('offers.index'));
    $this->assertDatabaseHas('offers', ['name' => 'Updated Offer']);
});

it('can delete an offer', function () {
    $offer = Offer::factory()->create(['menu_id' => $this->menu->id]);

    $response = $this->delete(route('offers.destroy', $offer));

    $response->assertRedirect(route('offers.index'));
    $this->assertDatabaseMissing('offers', ['id' => $offer->id]);
});

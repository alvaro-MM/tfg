<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->actingAs($this->admin);
});

it('shows users index without search', function () {

    User::factory()->count(3)->create();

    $response = $this->get(route('users.index'));

    $response->assertStatus(200);
    $response->assertViewHas('users');
    $response->assertViewHas('search', null);
});

it('filters users by name', function () {

    User::factory()->create(['name' => 'Juan Perez']);
    User::factory()->create(['name' => 'Carlos Lopez']);

    $response = $this->get(route('users.index', [
        'search' => 'Juan'
    ]));

    $response->assertStatus(200);
    $response->assertSee('Juan Perez');
    $response->assertDontSee('Carlos Lopez');
});

it('filters users by email', function () {

    User::factory()->create(['email' => 'test@example.com']);
    User::factory()->create(['email' => 'other@example.com']);

    $response = $this->get(route('users.index', [
        'search' => 'test@'
    ]));

    $response->assertStatus(200);
    $response->assertSee('test@example.com');
    $response->assertDontSee('other@example.com');
});

it('can view edit user page', function () {

    $user = User::factory()->create();

    $response = $this->get(route('users.edit', $user));

    $response->assertStatus(200);
    $response->assertViewHas('user');
});

it('can update a user', function () {

    $user = User::factory()->create();

    $data = [
        'name' => 'Nombre actualizado',
        'email' => 'nuevo@email.com',
    ];

    $response = $this->put(route('users.update', $user), $data);

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nombre actualizado',
        'email' => 'nuevo@email.com',
    ]);
});

it('cannot update user with duplicated email', function () {

    $existing = User::factory()->create([
        'email' => 'pepe@email.com',
    ]);

    $user = User::factory()->create();

    $response = $this->from(route('users.edit', $user))
        ->put(route('users.update', $user), [
            'name' => 'Pepe',
            'email' => 'pepe@email.com',
        ]);

    $response->assertRedirect(route('users.edit', $user));
    $response->assertSessionHasErrors('email');
});

it('requires name when updating user', function () {

    $user = User::factory()->create();

    $response = $this->put(route('users.update', $user), [
        'name' => '',
        'email' => 'pepe@email.com',
    ]);

    $response->assertSessionHasErrors('name');
});

it('can delete a user', function () {

    $user = User::factory()->create();

    $response = $this->delete(route('users.destroy', $user));

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

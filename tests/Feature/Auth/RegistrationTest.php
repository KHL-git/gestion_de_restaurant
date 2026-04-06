<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => User::ROLE_CLIENT,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    expect(auth()->user()->role)->toBe(User::ROLE_CLIENT);
});

test('first admin can register when no administrator exists', function () {
    $response = $this->post('/register', [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => User::ROLE_ADMIN,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
    expect(auth()->user()->role)->toBe(User::ROLE_ADMIN);
});

test('public registration cannot create an admin when one already exists', function () {
    User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Second Admin',
        'email' => 'second-admin@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => User::ROLE_ADMIN,
    ]);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('role');
    $this->assertGuest();
});

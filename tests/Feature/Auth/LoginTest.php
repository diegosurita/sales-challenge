<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\from;
use function Pest\Laravel\post;

it('should authenticate a user with valid credentials and redirect to dashboard', function () {
    $user = User::factory()->create();

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    assertAuthenticatedAs($user);
});

it('should redirect back with a failed error when credentials are invalid', function () {
    $user = User::factory()->create();

    $response = from(route('home'))->post(route('login'), [
        'email' => $user->email,
        'password' => 'invalid-password',
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHasErrors([
        'failed' => __('auth.failed'),
    ]);
    assertGuest();
});
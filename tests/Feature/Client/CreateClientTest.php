<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use function Pest\Laravel\actingAs;

it('should create a client and redirect to index', function () {
    $user = User::factory()->create();

    $clientData = ['name' => 'New Client'];

    $response = actingAs($user)->post(route('clients.store'), $clientData);

    $response->assertRedirect(route('clients.index'));

    expect(Client::where('name', 'New Client')->exists())->toBeTrue();
});

it('should validate name is required', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->post(route('clients.store'), []);

    $response->assertSessionHasErrors('name');
});
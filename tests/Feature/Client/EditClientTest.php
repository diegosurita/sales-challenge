<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;

use function Pest\Laravel\actingAs;

it('should render edit form with client data', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['name' => 'Existing Client']);

    $response = actingAs($user)->get(route('clients.edit', $client->id));

    $response->assertInertia(fn ($page) => $page
        ->component('Client/ClientForm')
        ->has('client')
        ->where('client.id', $client->id)
        ->where('client.name', 'Existing Client')
    );
});

it('should return 404 for non-existent client', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('clients.edit', 999999));

    $response->assertNotFound();
});

<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use function Pest\Laravel\actingAs;

it('should update a client and redirect to index', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['name' => 'Original Name']);

    $updateData = ['name' => 'Updated Name'];

    $response = actingAs($user)->put(route('clients.update', $client->id), $updateData);

    $response->assertRedirect(route('clients.index'));

    $client->refresh();
    expect($client->name)->toBe('Updated Name');
});

it('should validate name is required on update', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $response = actingAs($user)->put(route('clients.update', $client->id), []);

    $response->assertSessionHasErrors('name');
});
<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use function Pest\Laravel\actingAs;

it('should return a list of clients for authenticated user', function () {
    $user = User::factory()->create();

    $clients = Client::factory()->count(3)->create();

    $response = actingAs($user)->get(route('clients.index'));

    $response->assertOk();
});
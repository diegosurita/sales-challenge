<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should return a list of services for authenticated user', function () {
    $user = User::factory()->create();

    $services = Service::factory()->count(3)->create();

    $response = actingAs($user)->get(route('services.index'));

    $response->assertInertia(fn ($page) => $page
        ->component('Service/ServicesList')
        ->has('services', 3)
        ->where('services.0.name', $services[0]->name)
        ->where('services.1.name', $services[1]->name)
        ->where('services.2.name', $services[2]->name)
    );
});

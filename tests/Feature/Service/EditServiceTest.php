<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should render edit form with service data', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create(['name' => 'Existing Service', 'price' => 89.50]);

    $response = actingAs($user)->get(route('services.edit', $service->id));

    $response->assertInertia(fn ($page) => $page
        ->component('Service/ServiceForm')
        ->has('service')
        ->has('products')
        ->where('service.id', $service->id)
        ->where('service.name', 'Existing Service')
        ->where('service.price', (float) $service->price)
        ->where('service.product', null)
    );
});

it('should return 404 for non-existent service', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('services.edit', 999999));

    $response->assertNotFound();
});

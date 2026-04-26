<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should create a service and redirect to index', function () {
    $user = User::factory()->create();

    $serviceData = ['name' => 'New Service', 'price' => 149.99];

    $response = actingAs($user)->post(route('services.store'), $serviceData);

    $response->assertRedirect(route('services.index'));

    $service = Service::where('name', 'New Service')->first();

    expect($service)->not->toBeNull();
    expect((float) $service->price)->toBe(149.99);
});

it('should validate name is required', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->post(route('services.store'), ['price' => 10]);

    $response->assertSessionHasErrors('name');
});

it('should validate price is required and numeric', function () {
    $user = User::factory()->create();

    $missingPriceResponse = actingAs($user)->post(route('services.store'), ['name' => 'Service']);
    $invalidPriceResponse = actingAs($user)->post(route('services.store'), ['name' => 'Service', 'price' => 'abc']);

    $missingPriceResponse->assertSessionHasErrors('price');
    $invalidPriceResponse->assertSessionHasErrors('price');
});

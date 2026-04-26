<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
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
    expect($service->product_id)->toBeNull();
});

it('should create a service with a product dependency', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $serviceData = ['name' => 'Linked Service', 'price' => 99.00, 'product_id' => $product->id];

    $response = actingAs($user)->post(route('services.store'), $serviceData);

    $response->assertRedirect(route('services.index'));

    $service = Service::where('name', 'Linked Service')->first();

    expect($service)->not->toBeNull();
    expect($service->product_id)->toBe($product->id);
});

it('should validate product_id exists when provided', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->post(route('services.store'), [
        'name' => 'Service',
        'price' => 10,
        'product_id' => 999999,
    ]);

    $response->assertSessionHasErrors('product_id');
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

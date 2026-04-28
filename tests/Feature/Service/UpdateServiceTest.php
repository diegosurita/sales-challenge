<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should update a service and redirect to index', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create(['name' => 'Original Service', 'price' => 100]);

    $updateData = ['name' => 'Updated Service', 'price' => 199.99];

    $response = actingAs($user)->put(route('services.update', $service->id), $updateData);

    $response->assertRedirect(route('services.index'));

    $service->refresh();

    expect($service->name)->toBe('Updated Service');
    expect((float) $service->price)->toBe(199.99);
});

it('should validate required fields on update', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create();

    $response = actingAs($user)->put(route('services.update', $service->id), []);

    $response->assertSessionHasErrors(['name', 'price']);
});

it('should validate product_id exists on update when provided', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create();

    $response = actingAs($user)->put(route('services.update', $service->id), [
        'name' => 'Updated Service',
        'price' => 199.99,
        'product_id' => 999999,
    ]);

    $response->assertSessionHasErrors('product_id');
});

it('should update product_id when provided and existing', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create(['product_id' => null]);
    $product = Product::factory()->create();

    $response = actingAs($user)->put(route('services.update', $service->id), [
        'name' => 'Updated Service',
        'price' => 99.99,
        'product_id' => $product->id,
    ]);

    $response->assertRedirect(route('services.index'));

    $service->refresh();

    expect($service->product_id)->toBe($product->id);
});

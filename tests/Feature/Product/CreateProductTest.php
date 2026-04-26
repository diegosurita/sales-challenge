<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use function Pest\Laravel\actingAs;

it('should create a product and redirect to index', function () {
    $user = User::factory()->create();

    $productData = ['name' => 'New Product', 'price' => 149.99];

    $response = actingAs($user)->post(route('products.store'), $productData);

    $response->assertRedirect(route('products.index'));

    $product = Product::where('name', 'New Product')->first();

    expect($product)->not->toBeNull();
    expect((float) $product->price)->toBe(149.99);
});

it('should validate name is required', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->post(route('products.store'), ['price' => 10]);

    $response->assertSessionHasErrors('name');
});

it('should validate price is required and numeric', function () {
    $user = User::factory()->create();

    $missingPriceResponse = actingAs($user)->post(route('products.store'), ['name' => 'Product']);
    $invalidPriceResponse = actingAs($user)->post(route('products.store'), ['name' => 'Product', 'price' => 'abc']);

    $missingPriceResponse->assertSessionHasErrors('price');
    $invalidPriceResponse->assertSessionHasErrors('price');
});
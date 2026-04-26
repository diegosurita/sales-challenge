<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use function Pest\Laravel\actingAs;

it('should delete a product and return no content', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = actingAs($user)->delete(route('products.destroy', $product->id));

    $response->assertNoContent();

    $product->refresh();
    expect($product->trashed())->toBeTrue();
});

it('should return 404 when deleting non-existent product', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->delete(route('products.destroy', 999999));

    $response->assertNotFound();
});
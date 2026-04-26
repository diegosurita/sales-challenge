<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use function Pest\Laravel\actingAs;

it('should render edit form with product data', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Existing Product', 'price' => 89.50]);

    $response = actingAs($user)->get(route('products.edit', $product->id));

    $response->assertInertia(fn ($page) => $page
        ->component('Product/ProductForm')
        ->has('product')
        ->where('product.id', $product->id)
        ->where('product.name', 'Existing Product')
        ->where('product.price', (float) $product->price)
    );
});

it('should return 404 for non-existent product', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('products.edit', 999999));

    $response->assertNotFound();
});
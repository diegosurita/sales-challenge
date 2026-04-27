<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use function Pest\Laravel\actingAs;

it('should return a list of products for authenticated user', function () {
    $user = User::factory()->create();

    $products = Product::factory()->count(3)->create();

    $response = actingAs($user)->get(route('products.index'));

    $response->assertInertia(fn ($page) => $page
        ->component('Product/ProductsList')
        ->has('products', 3)
        ->where('products.0.name', $products[0]->name)
        ->where('products.0.stock_count', $products[0]->stock_count)
        ->where('products.1.name', $products[1]->name)
        ->where('products.1.stock_count', $products[1]->stock_count)
        ->where('products.2.name', $products[2]->name)
        ->where('products.2.stock_count', $products[2]->stock_count)
    );
});

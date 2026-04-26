<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use function Pest\Laravel\actingAs;

it('should update a product and redirect to index', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Original Product', 'price' => 100]);

    $updateData = ['name' => 'Updated Product', 'price' => 199.99];

    $response = actingAs($user)->put(route('products.update', $product->id), $updateData);

    $response->assertRedirect(route('products.index'));

    $product->refresh();

    expect($product->name)->toBe('Updated Product');
    expect((float) $product->price)->toBe(199.99);
});

it('should validate required fields on update', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $response = actingAs($user)->put(route('products.update', $product->id), []);

    $response->assertSessionHasErrors(['name', 'price']);
});
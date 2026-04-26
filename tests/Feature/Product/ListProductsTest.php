<?php

use Illuminate\Support\Facades\DB;
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

it('should resolve stock_count from ledger when product stock_count is null', function () {
    $user = User::factory()->create();

    $product = Product::factory()->create([
        'stock_count' => null,
    ]);

    DB::table('products_stock_ledger')->insert([
        'product_id' => $product->id,
        'reason' => 'initial load',
        'quantity' => 8,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('products_stock_ledger')->insert([
        'product_id' => $product->id,
        'reason' => 'sale',
        'quantity' => -3,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = actingAs($user)->get(route('products.index'));

    $response->assertInertia(fn ($page) => $page
        ->component('Product/ProductsList')
        ->where('products.0.id', $product->id)
        ->where('products.0.stock_count', 5)
    );
});
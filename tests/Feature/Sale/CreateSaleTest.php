<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\ProductStockLedger;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should show the create sale form to an authenticated user', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('sales.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Sale/SaleForm')
        ->has('clients')
        ->has('products')
        ->has('services')
    );
});

it('should create a sale with a product and redirect to index', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $product = Product::factory()->create(['stock_count' => 5, 'price' => 100.00]);

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $client->id,
        'products' => [['product_id' => $product->id]],
        'services' => [],
    ]);

    $response->assertRedirect(route('sales.index'));

    $sale = Sale::where('client_id', $client->id)->first();

    expect($sale)->not->toBeNull();
    expect($sale->status)->toBe('pending');
    expect(SaleProduct::where('sale_id', $sale->id)->where('product_id', $product->id)->exists())->toBeTrue();

    $product->refresh();
    expect($product->stock_count)->toBe(4);

    $ledgerEntry = ProductStockLedger::where('product_id', $product->id)
        ->where('quantity', -1)
        ->where('reason', 'sale')
        ->first();
    expect($ledgerEntry)->not->toBeNull();
});

it('should create a sale with a service and redirect to index', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $service = Service::factory()->create(['available' => true, 'price' => 50.00]);

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $client->id,
        'products' => [],
        'services' => [['service_id' => $service->id]],
    ]);

    $response->assertRedirect(route('sales.index'));

    $sale = Sale::where('client_id', $client->id)->first();
    expect($sale)->not->toBeNull();
});

it('should require a client to create a sale', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_count' => 5]);

    $response = actingAs($user)->post(route('sales.store'), [
        'products' => [['product_id' => $product->id]],
        'services' => [],
    ]);

    $response->assertSessionHasErrors('client_id');
});

it('should require at least one product or service', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $client->id,
        'products' => [],
        'services' => [],
    ]);

    $response->assertSessionHasErrors('items');
});

it('should reject a product with insufficient stock', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $product = Product::factory()->create(['stock_count' => 0, 'name' => 'Out of Stock Item']);

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $client->id,
        'products' => [['product_id' => $product->id]],
        'services' => [],
    ]);

    $response->assertSessionHasErrors('products');
});

it('should reject an unavailable service', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $service = Service::factory()->create(['available' => false, 'name' => 'Unavailable Service']);

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $client->id,
        'products' => [],
        'services' => [['service_id' => $service->id]],
    ]);

    $response->assertSessionHasErrors('services');
});

it('should reject a product already sold to 3 different clients on the same day', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_count' => 10, 'name' => 'Popular Item']);
    $newClient = Client::factory()->create();

    // Create 3 existing sales for this product on today, each for a different client
    for ($i = 0; $i < 3; $i++) {
        $existingClient = Client::factory()->create();
        $sale = Sale::factory()->create(['client_id' => $existingClient->id]);
        SaleProduct::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
        ]);
    }

    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $newClient->id,
        'products' => [['product_id' => $product->id]],
        'services' => [],
    ]);

    $response->assertSessionHasErrors('products');
});

it('should allow a product sold to 3 clients when client is one of them', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_count' => 10]);
    $existingClients = Client::factory()->count(2)->create();
    $repeatingClient = Client::factory()->create();

    // This client already bought the product today
    $sale = Sale::factory()->create(['client_id' => $repeatingClient->id]);
    SaleProduct::factory()->create(['sale_id' => $sale->id, 'product_id' => $product->id]);

    // Two other clients also bought it today
    foreach ($existingClients as $client) {
        $s = Sale::factory()->create(['client_id' => $client->id]);
        SaleProduct::factory()->create(['sale_id' => $s->id, 'product_id' => $product->id]);
    }

    // The repeating client should still be able to buy (already in the set of 3)
    $response = actingAs($user)->post(route('sales.store'), [
        'client_id' => $repeatingClient->id,
        'products' => [['product_id' => $product->id]],
        'services' => [],
    ]);

    $response->assertRedirect(route('sales.index'));
});

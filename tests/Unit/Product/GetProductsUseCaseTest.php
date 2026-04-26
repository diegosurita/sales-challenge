<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Core\UseCases\GetProductsStockFromLedgerUseCase;
use Module\Product\Core\UseCases\GetProductsUseCase;

it('should return products using stored stock when stock_count is not null', function () {
    $products = [
        new ProductEntity(id: 1, name: 'Product 1', price: 10, stockCount: 12),
        new ProductEntity(id: 2, name: 'Product 2', price: 20, stockCount: 7),
    ];

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($products);
    $bulkLedgerUseCase = mock(GetProductsStockFromLedgerUseCase::class);
    $bulkLedgerUseCase->shouldNotReceive('execute');

    $useCase = new GetProductsUseCase(
        productRepository: $repository,
        getProductsStockFromLedgerUseCase: $bulkLedgerUseCase
    );

    $result = $useCase->execute();

    expect($result)->toBe($products);
});

it('should resolve stock from ledger when product stock_count is null', function () {
    $products = [
        new ProductEntity(id: 1, name: 'Product 1', price: 10, stockCount: null),
        new ProductEntity(id: 2, name: 'Product 2', price: 20, stockCount: 5),
    ];

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($products);

    $bulkLedgerUseCase = mock(GetProductsStockFromLedgerUseCase::class);
    $bulkLedgerUseCase->shouldReceive('execute')->with([1])->once()->andReturn([
        1 => 18,
    ]);

    $useCase = new GetProductsUseCase(
        productRepository: $repository,
        getProductsStockFromLedgerUseCase: $bulkLedgerUseCase
    );

    $result = $useCase->execute();

    expect($result[0]->getStockCount())->toBe(18);
    expect($result[1]->getStockCount())->toBe(5);
});

it('should fallback to zero when product is missing in bulk ledger result', function () {
    $products = [
        new ProductEntity(id: 1, name: 'Product 1', price: 10, stockCount: null),
    ];

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($products);

    $bulkLedgerUseCase = mock(GetProductsStockFromLedgerUseCase::class);
    $bulkLedgerUseCase->shouldReceive('execute')->with([1])->once()->andReturn([]);

    $useCase = new GetProductsUseCase(
        productRepository: $repository,
        getProductsStockFromLedgerUseCase: $bulkLedgerUseCase
    );

    $result = $useCase->execute();

    expect($result[0]->getStockCount())->toBe(0);
});
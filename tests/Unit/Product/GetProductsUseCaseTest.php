<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Core\UseCases\GetProductsUseCase;

it('should return products with their stored stock_count', function () {
    $products = [
        new ProductEntity(id: 1, name: 'Product 1', price: 10, stockCount: 12),
        new ProductEntity(id: 2, name: 'Product 2', price: 20, stockCount: 7),
    ];

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($products);

    $useCase = new GetProductsUseCase(
        productRepository: $repository,
    );

    $result = $useCase->execute();

    expect($result)->toBe($products);
});

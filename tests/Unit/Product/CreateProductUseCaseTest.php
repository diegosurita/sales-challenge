<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\UseCases\CreateProductUseCase;
use Module\Product\Core\UseCases\RegisterProductStockUseCase;

it('should create a product via repository', function () {
    $dto = new ProductFormDTO(
        name: 'New Product',
        price: 150,
    );

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('createProduct')->once()->with($dto)->andReturn(1);

    $registerProductStockUseCase = mock(RegisterProductStockUseCase::class);
    $registerProductStockUseCase->shouldNotReceive('execute');

    $useCase = new CreateProductUseCase(
        productRepository: $repository,
        registerProductStockUseCase: $registerProductStockUseCase,
    );

    $useCase->execute($dto);
});

it('should create a product with opening balance ledger entry when stock count is provided', function () {
    $dto = new ProductFormDTO(
        name: 'New Product',
        price: 150,
        stockCount: 10,
    );

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('createProduct')->once()->with($dto)->andReturn(42);

    $registerProductStockUseCase = mock(RegisterProductStockUseCase::class);
    $registerProductStockUseCase->shouldReceive('execute')->once();

    $useCase = new CreateProductUseCase(
        productRepository: $repository,
        registerProductStockUseCase: $registerProductStockUseCase,
    );

    $useCase->execute($dto);
});
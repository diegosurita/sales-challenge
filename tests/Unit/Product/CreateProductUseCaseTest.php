<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\UseCases\CreateProductUseCase;

it('should create a product via repository', function () {
    $dto = new ProductFormDTO(
        name: 'New Product',
        price: 150,
    );

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('createProduct')->once()->with($dto);

    $useCase = new CreateProductUseCase($repository);

    $useCase->execute($dto);
});
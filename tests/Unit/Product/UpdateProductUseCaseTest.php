<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\UseCases\UpdateProductUseCase;

it('should update a product via repository', function () {
    $dto = new ProductFormDTO(
        name: 'Updated Product',
        price: 250,
        id: 1,
    );

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('updateProduct')->once()->with($dto);

    $useCase = new UpdateProductUseCase($repository);

    $useCase->execute($dto);
});
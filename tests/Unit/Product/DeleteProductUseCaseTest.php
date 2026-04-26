<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\UseCases\DeleteProductUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should delete a product via repository', function () {
    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('delete')->once()->with(1);

    $useCase = new DeleteProductUseCase($repository);

    $useCase->execute(1);
});

it('should throw NotFoundException when deleting a non-existent product', function () {
    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('delete')->with(999)->once()->andThrow(new NotFoundException('Product', 999));

    $useCase = new DeleteProductUseCase($repository);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});
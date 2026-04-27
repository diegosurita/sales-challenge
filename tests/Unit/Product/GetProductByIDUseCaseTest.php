<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Core\UseCases\GetProductByIDUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should return product with stock_count', function () {
    $product = new ProductEntity(id: 1, name: 'Test Product', price: 100, stockCount: 9);

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(1)->once()->andReturn($product);

    $useCase = new GetProductByIDUseCase($repository);

    $result = $useCase->execute(1);

    expect($result)->toBe($product);
});

it('should throw NotFoundException when product not found', function () {
    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(999)->once()->andThrow(new NotFoundException('Product', 999));

    $useCase = new GetProductByIDUseCase($repository);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});

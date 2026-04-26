<?php

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Core\UseCases\GetProductStockFromLedgerUseCase;
use Module\Product\Core\UseCases\GetProductByIDUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should return stored stock when product stock_count is not null', function () {
    $product = new ProductEntity(id: 1, name: 'Test Product', price: 100, stockCount: 9);

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(1)->once()->andReturn($product);
    $ledgerUseCase = mock(GetProductStockFromLedgerUseCase::class);
    $ledgerUseCase->shouldNotReceive('execute');

    $useCase = new GetProductByIDUseCase($repository, $ledgerUseCase);

    $result = $useCase->execute(1);

    expect($result)->toBe($product);
});

it('should resolve stock from ledger when product stock_count is null', function () {
    $product = new ProductEntity(id: 1, name: 'Test Product', price: 100, stockCount: null);

    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(1)->once()->andReturn($product);

    $ledgerUseCase = mock(GetProductStockFromLedgerUseCase::class);
    $ledgerUseCase->shouldReceive('execute')->with(1)->once()->andReturn(14);

    $useCase = new GetProductByIDUseCase($repository, $ledgerUseCase);

    $result = $useCase->execute(1);

    expect($result->getStockCount())->toBe(14);
});

it('should throw NotFoundException when product not found', function () {
    $repository = mock(ProductRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(999)->once()->andThrow(new NotFoundException('Product', 999));
    $ledgerUseCase = mock(GetProductStockFromLedgerUseCase::class);

    $useCase = new GetProductByIDUseCase($repository, $ledgerUseCase);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});
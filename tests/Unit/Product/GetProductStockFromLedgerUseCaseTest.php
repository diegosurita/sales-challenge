<?php

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Core\UseCases\GetProductStockFromLedgerUseCase;

it('should return stock sum from stock ledger repository', function () {
    $repository = mock(ProductStockLedgerRepositoryContract::class);
    $repository->shouldReceive('getStockSumByProductID')->with(10)->once()->andReturn(21);
    $repository->shouldReceive('getStockSumsByProductIDs')->never();

    $useCase = new GetProductStockFromLedgerUseCase($repository);

    $result = $useCase->execute(10);

    expect($result)->toBe(21);
});

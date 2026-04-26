<?php

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Core\UseCases\GetProductsStockFromLedgerUseCase;

it('should return grouped stock sums from stock ledger repository', function () {
    $repository = mock(ProductStockLedgerRepositoryContract::class);
    $repository->shouldReceive('getStockSumsByProductIDs')->with([10, 11])->once()->andReturn([
        10 => 6,
        11 => 3,
    ]);

    $useCase = new GetProductsStockFromLedgerUseCase($repository);

    $result = $useCase->execute([10, 11]);

    expect($result)->toBe([
        10 => 6,
        11 => 3,
    ]);
});

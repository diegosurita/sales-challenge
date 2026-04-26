<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;

class GetProductStockFromLedgerUseCase
{
    public function __construct(
        private readonly ProductStockLedgerRepositoryContract $stockLedgerRepository,
    ) {
    }

    public function execute(int $productId): int
    {
        return $this->stockLedgerRepository->getStockSumByProductID($productId);
    }
}

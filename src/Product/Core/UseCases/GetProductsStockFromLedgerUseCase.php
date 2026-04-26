<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;

class GetProductsStockFromLedgerUseCase
{
    public function __construct(
        private readonly ProductStockLedgerRepositoryContract $stockLedgerRepository,
    ) {
    }

    /**
     * @param int[] $productIds
     * @return array<int, int>
     */
    public function execute(array $productIds): array
    {
        return $this->stockLedgerRepository->getStockSumsByProductIDs($productIds);
    }
}

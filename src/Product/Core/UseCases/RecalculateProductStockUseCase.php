<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;

class RecalculateProductStockUseCase
{
    public function __construct(
        private readonly ProductStockLedgerRepositoryContract $stockLedgerRepository,
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    public function execute(int $productId): void
    {
        $stockCount = $this->stockLedgerRepository->getStockSumByProductID(productId: $productId);

        $this->productRepository->updateStockCount(
            productId: $productId,
            stockCount: $stockCount,
        );
    }
}

<?php

namespace Module\Product\Core\Contracts;

interface ProductStockLedgerRepositoryContract
{
    public function getStockSumByProductID(int $productId): int;

    /**
     * @param int[] $productIds
     * @return array<int, int> The keys are product IDs and the values are the stock sums
     */
    public function getStockSumsByProductIDs(array $productIds): array;
}

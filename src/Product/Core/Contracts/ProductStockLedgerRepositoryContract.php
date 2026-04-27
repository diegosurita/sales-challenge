<?php

namespace Module\Product\Core\Contracts;

use Module\Product\Core\DTOs\RegisterStockDTO;
use Module\Product\Core\Entities\ProductStockLedgerEntity;

interface ProductStockLedgerRepositoryContract
{
    public function getStockSumByProductID(int $productId): int;

    /**
     * @param int[] $productIds
     * @return array<int, int> The keys are product IDs and the values are the stock sums
     */
    public function getStockSumsByProductIDs(array $productIds): array;

    public function create(RegisterStockDTO $dto): void;

    /**
     * @return ProductStockLedgerEntity[]
     */
    public function getByProductID(int $productId): array;
}

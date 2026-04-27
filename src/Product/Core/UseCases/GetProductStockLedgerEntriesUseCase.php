<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Core\Entities\ProductStockLedgerEntity;

class GetProductStockLedgerEntriesUseCase
{
    public function __construct(
        private readonly ProductStockLedgerRepositoryContract $repository,
    ) {
    }

    /**
     * @return ProductStockLedgerEntity[]
     */
    public function execute(int $productId): array
    {
        return $this->repository->getByProductID(productId: $productId);
    }
}

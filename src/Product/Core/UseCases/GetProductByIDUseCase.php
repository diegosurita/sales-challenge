<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductByIDUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly GetProductStockFromLedgerUseCase $getProductStockFromLedgerUseCase,
    ) {
    }

    public function execute(int $productId): ProductEntity
    {
        $product = $this->productRepository->getByID($productId);

        if ($product->getStockCount() !== null) {
            return $product;
        }

        $stockCount = $this->getProductStockFromLedgerUseCase->execute($productId);

        return $product->setCount($stockCount);
    }
}
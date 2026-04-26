<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly GetProductsStockFromLedgerUseCase $getProductsStockFromLedgerUseCase,
    ) {
    }

    /**
     * @return ProductEntity[]
     */
    public function execute(): array
    {
        $products = $this->productRepository->getAll();

        $productsWithoutStockCount = array_filter(
            $products,
            fn (ProductEntity $product): bool => $product->getStockCount() === null,
        );

        if ($productsWithoutStockCount === []) {
            return $products;
        }

        $productIDsWithoutStockCount = array_map(
            fn (ProductEntity $product): int => $product->getId(),
            $productsWithoutStockCount,
        );

        $ledgerStockByProductID = $this->getProductsStockFromLedgerUseCase->execute($productIDsWithoutStockCount);

        return array_map(function (ProductEntity $product) use ($ledgerStockByProductID): ProductEntity {
            if ($product->getStockCount() !== null) {
                return $product;
            }

            $stockCount = $ledgerStockByProductID[$product->getId()] ?? 0;

            return $product->setCount($stockCount);
        }, $products);
    }
}
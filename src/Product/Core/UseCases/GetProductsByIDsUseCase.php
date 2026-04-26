<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductsByIDsUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly GetProductsStockFromLedgerUseCase $getProductsStockFromLedgerUseCase,
    ) {}

    /**
     * @param  int[]  $ids
     * @return array<int, ProductEntity>
     */
    public function execute(array $ids): array
    {
        $products = $this->productRepository->getManyByIDs($ids);

        $idsWithoutStockCount = array_keys(array_filter(
            $products,
            fn (ProductEntity $product): bool => $product->getStockCount() === null,
        ));

        if ($idsWithoutStockCount === []) {
            return $products;
        }

        $ledgerStockByProductID = $this->getProductsStockFromLedgerUseCase->execute($idsWithoutStockCount);

        return array_map(function (ProductEntity $product) use ($ledgerStockByProductID): ProductEntity {
            if ($product->getStockCount() !== null) {
                return $product;
            }

            return $product->setCount($ledgerStockByProductID[$product->getId()] ?? 0);
        }, $products);
    }
}

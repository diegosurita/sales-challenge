<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    /**
     * @return ProductEntity[]
     */
    public function execute(): array
    {
        return $this->productRepository->getAll();
    }
}
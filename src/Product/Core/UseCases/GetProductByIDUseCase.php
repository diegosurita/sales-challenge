<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductByIDUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {}

    public function execute(int $productId): ProductEntity
    {
        return $this->productRepository->getByID($productId);
    }
}

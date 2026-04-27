<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Entities\ProductEntity;

class GetProductsByIDsUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {}

    /**
     * @param  int[]  $ids
     * @return array<int, ProductEntity>
     */
    public function execute(array $ids): array
    {
        return $this->productRepository->getManyByIDs($ids);
    }
}

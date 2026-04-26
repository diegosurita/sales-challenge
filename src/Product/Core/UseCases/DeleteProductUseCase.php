<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;

class DeleteProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    public function execute(int $id): void
    {
        $this->productRepository->delete($id);
    }
}
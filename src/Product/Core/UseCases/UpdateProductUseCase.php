<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;

class UpdateProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    public function execute(ProductFormDTO $dto): void
    {
        $this->productRepository->updateProduct($dto);
    }
}
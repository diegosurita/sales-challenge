<?php

namespace Module\Product\Core\Contracts;

use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\Entities\ProductEntity;

interface ProductRepositoryContract
{
    /**
     * @return ProductEntity[]
     */
    public function getAll(): array;

    public function createProduct(ProductFormDTO $dto): void;

    public function getByID(int $id): ProductEntity;

    public function updateProduct(ProductFormDTO $dto): void;

    public function delete(int $id): void;
}
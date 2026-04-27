<?php

namespace Module\Product\Core\DTOs;

final class ProductFormDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?int $id = null,
        public readonly int $stockCount = 0,
    ) {}
}

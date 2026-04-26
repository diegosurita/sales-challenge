<?php

namespace Module\Sale\Core\DTOs;

final class SaleCreateProductItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly float $price,
    ) {}
}

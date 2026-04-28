<?php

namespace Module\Sale\Core\DTOs;

final class SaleFormProductItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
    ) {}
}

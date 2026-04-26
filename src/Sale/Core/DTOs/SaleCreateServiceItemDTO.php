<?php

namespace Module\Sale\Core\DTOs;

final class SaleCreateServiceItemDTO
{
    public function __construct(
        public readonly int $serviceId,
        public readonly float $price,
    ) {}
}

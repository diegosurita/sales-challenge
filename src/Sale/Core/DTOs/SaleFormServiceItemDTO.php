<?php

namespace Module\Sale\Core\DTOs;

final class SaleFormServiceItemDTO
{
    public function __construct(
        public readonly int $serviceId,
    ) {}
}

<?php

namespace Module\Sale\Core\DTOs;

final class SaleFormDTO
{
    /**
     * @param  SaleFormProductItemDTO[]  $products
     * @param  SaleFormServiceItemDTO[]  $services
     */
    public function __construct(
        public readonly int $clientId,
        public readonly array $products,
        public readonly array $services,
    ) {}
}

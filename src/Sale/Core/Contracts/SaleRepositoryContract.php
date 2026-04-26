<?php

namespace Module\Sale\Core\Contracts;

use Module\Sale\Core\DTOs\SaleCreateProductItemDTO;
use Module\Sale\Core\DTOs\SaleCreateServiceItemDTO;
use Module\Sale\Core\Entities\SaleEntity;

interface SaleRepositoryContract
{
    /**
     * @return SaleEntity[]
     */
    public function getAll(): array;

    /**
     * @param  SaleCreateProductItemDTO[]  $products
     * @param  SaleCreateServiceItemDTO[]  $services
     */
    public function createSale(int $clientId, array $products, array $services): void;

    /**
     * @return int[]
     */
    public function getDistinctClientIdsForProductToday(int $productId): array;
}

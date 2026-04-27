<?php

namespace Module\Product\Infrastructure\Gateways;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Shared\Core\Contracts\ProductModuleGatewayContract;

class ProductModuleGateway implements ProductModuleGatewayContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {}

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingProducts(int $limit): array
    {
        return $this->productRepository->getTopSellingProducts(limit: $limit);
    }
}

<?php

namespace Module\Shared\Core\Contracts;

interface ProductModuleGatewayContract
{
    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingProducts(int $limit): array;
}

<?php

namespace Module\Sale\Infrastructure\Gateways;

use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Shared\Core\Contracts\SaleModuleGatewayContract;

class SaleModuleGateway implements SaleModuleGatewayContract
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
    ) {}

    public function getCurrentMonthRevenue(): float
    {
        return $this->saleRepository->getCurrentMonthRevenue();
    }

    /**
     * @return array<int, array{month: string, revenue: float}>
     */
    public function getMonthlyRevenueLast12Months(): array
    {
        return $this->saleRepository->getMonthlyRevenueLast12Months();
    }
}

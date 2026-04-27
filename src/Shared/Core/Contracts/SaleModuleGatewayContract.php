<?php

namespace Module\Shared\Core\Contracts;

interface SaleModuleGatewayContract
{
    public function getCurrentMonthRevenue(): float;

    /**
     * @return array<int, array{month: string, revenue: float}>
     */
    public function getMonthlyRevenueLast12Months(): array;
}

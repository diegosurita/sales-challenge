<?php

namespace Module\Shared\Core\Contracts;

interface DashboardQueryServiceContract
{
    public function getCurrentMonthRevenue(): float;

    /**
     * @return array<int, array{month: string, revenue: float}>
     */
    public function getMonthlyRevenueLast12Months(): array;

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingProducts(int $limit): array;

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingServices(int $limit): array;
}

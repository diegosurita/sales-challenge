<?php

namespace Module\Shared\Core\DTOs;

final readonly class DashboardDataDTO
{
    /**
     * @param  array<int, array{month: string, revenue: float}>  $monthlyRevenue
     * @param  array<int, array{name: string, total_sold: int}>  $topProducts
     * @param  array<int, array{name: string, total_sold: int}>  $topServices
     */
    public function __construct(
        public readonly float $currentMonthRevenue,
        public readonly array $monthlyRevenue,
        public readonly array $topProducts,
        public readonly array $topServices,
    ) {}
}

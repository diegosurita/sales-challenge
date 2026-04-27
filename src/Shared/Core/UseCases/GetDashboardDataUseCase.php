<?php

namespace Module\Shared\Core\UseCases;

use Illuminate\Support\Facades\Concurrency;
use Module\Shared\Core\Contracts\DashboardQueryServiceContract;
use Module\Shared\Core\DTOs\DashboardDataDTO;

class GetDashboardDataUseCase
{
    public function __construct(
        private readonly DashboardQueryServiceContract $dashboardQueryService,
    ) {}

    public function execute(): DashboardDataDTO
    {
        [
            $currentMonthRevenue,
            $monthlyRevenue,
            $topProducts,
            $topServices,
        ] = Concurrency::run([
            fn () => $this->dashboardQueryService->getCurrentMonthRevenue(),
            fn () => $this->dashboardQueryService->getMonthlyRevenueLast12Months(),
            fn () => $this->dashboardQueryService->getTopSellingProducts(limit: 5),
            fn () => $this->dashboardQueryService->getTopSellingServices(limit: 5),
        ]);

        return new DashboardDataDTO(
            currentMonthRevenue: $currentMonthRevenue,
            monthlyRevenue: $monthlyRevenue,
            topProducts: $topProducts,
            topServices: $topServices,
        );
    }
}

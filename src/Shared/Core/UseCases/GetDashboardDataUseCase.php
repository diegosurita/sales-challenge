<?php

namespace Module\Shared\Core\UseCases;

use Illuminate\Support\Facades\Concurrency;
use Module\Shared\Core\Contracts\ClientModuleGatewayContract;
use Module\Shared\Core\Contracts\ProductModuleGatewayContract;
use Module\Shared\Core\Contracts\SaleModuleGatewayContract;
use Module\Shared\Core\Contracts\ServiceModuleGatewayContract;
use Module\Shared\Core\DTOs\DashboardDataDTO;

class GetDashboardDataUseCase
{
    public function __construct(
        private readonly SaleModuleGatewayContract $saleModuleGateway,
        private readonly ClientModuleGatewayContract $clientModuleGateway,
        private readonly ProductModuleGatewayContract $productModuleGateway,
        private readonly ServiceModuleGatewayContract $serviceModuleGateway,
    ) {}

    public function execute(): DashboardDataDTO
    {
        [
            $currentMonthRevenue,
            $monthlyRevenue,
            $topClients,
            $topProducts,
            $topServices,
        ] = Concurrency::run([
            fn () => $this->saleModuleGateway->getCurrentMonthRevenue(),
            fn () => $this->saleModuleGateway->getMonthlyRevenueLast12Months(),
            fn () => $this->clientModuleGateway->getTopClientsBySalesValue(limit: 5),
            fn () => $this->productModuleGateway->getTopSellingProducts(limit: 5),
            fn () => $this->serviceModuleGateway->getTopSellingServices(limit: 5),
        ]);

        return new DashboardDataDTO(
            currentMonthRevenue: $currentMonthRevenue,
            monthlyRevenue: $monthlyRevenue,
            topClients: $topClients,
            topProducts: $topProducts,
            topServices: $topServices,
        );
    }
}

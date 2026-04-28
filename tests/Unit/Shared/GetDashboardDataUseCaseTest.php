<?php

use Module\Shared\Core\Contracts\ClientModuleGatewayContract;
use Module\Shared\Core\Contracts\ProductModuleGatewayContract;
use Module\Shared\Core\Contracts\SaleModuleGatewayContract;
use Module\Shared\Core\Contracts\ServiceModuleGatewayContract;
use Module\Shared\Core\UseCases\GetDashboardDataUseCase;
use Tests\TestCase;

uses(TestCase::class);

it('should return dashboard data correctly', function () {
    config()->set('concurrency.default', 'sync');

    $saleModuleGateway = mock(SaleModuleGatewayContract::class);
    $saleModuleGateway->shouldReceive('getCurrentMonthRevenue')->once()->andReturn(1200.5);
    $saleModuleGateway->shouldReceive('getMonthlyRevenueLast12Months')->once()->andReturn([
        ['month' => 'Apr 2026', 'revenue' => 1200.5],
    ]);

    $clientModuleGateway = mock(ClientModuleGatewayContract::class);
    $clientModuleGateway->shouldReceive('getTopClientsBySalesValue')
        ->once()
        ->with(5)
        ->andReturn([
            ['name' => 'Client A', 'total_sales_value' => 1500.0],
        ]);

    $productModuleGateway = mock(ProductModuleGatewayContract::class);
    $productModuleGateway->shouldReceive('getTopSellingProducts')->once()->with(5)->andReturn([
        ['name' => 'Product A', 'total_sold' => 10],
    ]);

    $serviceModuleGateway = mock(ServiceModuleGatewayContract::class);
    $serviceModuleGateway->shouldReceive('getTopSellingServices')->once()->with(5)->andReturn([
        ['name' => 'Service A', 'total_sold' => 8],
    ]);

    $useCase = new GetDashboardDataUseCase(
        saleModuleGateway: $saleModuleGateway,
        clientModuleGateway: $clientModuleGateway,
        productModuleGateway: $productModuleGateway,
        serviceModuleGateway: $serviceModuleGateway,
    );

    $result = $useCase->execute();

    expect($result->currentMonthRevenue)->toBe(1200.5)
        ->and($result->monthlyRevenue)->toBe([
            ['month' => 'Apr 2026', 'revenue' => 1200.5],
        ])
        ->and($result->topClients)->toBe([
            ['name' => 'Client A', 'total_sales_value' => 1500.0],
        ])
        ->and($result->topProducts)->toBe([
            ['name' => 'Product A', 'total_sold' => 10],
        ])
        ->and($result->topServices)->toBe([
            ['name' => 'Service A', 'total_sold' => 8],
        ]);
});

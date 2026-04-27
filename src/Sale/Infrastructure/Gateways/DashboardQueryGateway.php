<?php

namespace Module\Sale\Infrastructure\Gateways;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleService;
use Module\Shared\Core\Contracts\DashboardQueryServiceContract;

class DashboardQueryGateway implements DashboardQueryServiceContract
{
    public function getCurrentMonthRevenue(): float
    {
        $now = CarbonImmutable::now();

        $productRevenue = SaleProduct::query()
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->whereYear('sales.created_at', $now->year)
            ->whereMonth('sales.created_at', $now->month)
            ->sum(DB::raw('sale_products.price * sale_products.quantity'));

        $serviceRevenue = SaleService::query()
            ->join('sales', 'sale_services.sale_id', '=', 'sales.id')
            ->whereYear('sales.created_at', $now->year)
            ->whereMonth('sales.created_at', $now->month)
            ->sum('sale_services.price');

        return (float) $productRevenue + (float) $serviceRevenue;
    }

    /**
     * @return array<int, array{month: string, revenue: float}>
     */
    public function getMonthlyRevenueLast12Months(): array
    {
        $now = CarbonImmutable::now();
        $startDate = $now->startOfMonth()->subMonths(11);

        $productRevenues = SaleProduct::query()
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->where('sales.created_at', '>=', $startDate)
            ->select([
                DB::raw('EXTRACT(YEAR FROM sales.created_at) as year'),
                DB::raw('EXTRACT(MONTH FROM sales.created_at) as month_number'),
                DB::raw('SUM(sale_products.price * sale_products.quantity) as total'),
            ])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM sales.created_at)'), DB::raw('EXTRACT(MONTH FROM sales.created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->year.'-'.(int) $row->month_number);

        $serviceRevenues = SaleService::query()
            ->join('sales', 'sale_services.sale_id', '=', 'sales.id')
            ->where('sales.created_at', '>=', $startDate)
            ->select([
                DB::raw('EXTRACT(YEAR FROM sales.created_at) as year'),
                DB::raw('EXTRACT(MONTH FROM sales.created_at) as month_number'),
                DB::raw('SUM(sale_services.price) as total'),
            ])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM sales.created_at)'), DB::raw('EXTRACT(MONTH FROM sales.created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->year.'-'.(int) $row->month_number);

        $result = [];

        for ($monthOffset = 11; $monthOffset >= 0; $monthOffset--) {
            $date = $now->startOfMonth()->subMonths($monthOffset);
            $key = $date->year.'-'.$date->month;
            $monthLabel = $date->format('M Y');

            $productTotal = isset($productRevenues[$key]) ? (float) $productRevenues[$key]->total : 0.0;
            $serviceTotal = isset($serviceRevenues[$key]) ? (float) $serviceRevenues[$key]->total : 0.0;

            $result[] = [
                'month' => $monthLabel,
                'revenue' => $productTotal + $serviceTotal,
            ];
        }

        return $result;
    }

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingProducts(int $limit): array
    {
        return SaleProduct::query()
            ->join('products', 'sale_products.product_id', '=', 'products.id')
            ->select([
                'products.name',
                DB::raw('SUM(sale_products.quantity) as total_sold'),
            ])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'total_sold' => (int) $row->total_sold,
            ])
            ->toArray();
    }

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingServices(int $limit): array
    {
        return SaleService::query()
            ->join('services', 'sale_services.service_id', '=', 'services.id')
            ->select([
                'services.name',
                DB::raw('COUNT(sale_services.id) as total_sold'),
            ])
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'total_sold' => (int) $row->total_sold,
            ])
            ->toArray();
    }
}

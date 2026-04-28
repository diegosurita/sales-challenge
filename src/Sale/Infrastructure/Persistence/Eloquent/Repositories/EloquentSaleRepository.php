<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\ProductStockLedger;
use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Core\DTOs\SaleCreateProductItemDTO;
use Module\Sale\Core\DTOs\SaleCreateServiceItemDTO;
use Module\Sale\Core\Entities\SaleEntity;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleService;
use Module\Shared\Core\Exceptions\NotFoundException;

class EloquentSaleRepository implements SaleRepositoryContract
{
    public function getAll(): array
    {
        return Sale::get()->map(fn (Sale $sale) => new SaleEntity(
            id: $sale->id,
            clientId: $sale->client_id,
            clientName: $sale->client->name,
            createdAt: $sale->created_at->toImmutable(),
            updatedAt: $sale->updated_at->toImmutable(),
        ))->toArray();
    }

    public function getByID(int $id): SaleEntity
    {
        /** @var Sale|null $sale */
        $sale = Sale::with(['client', 'saleProducts.product', 'saleServices.service'])->find($id);

        if ($sale === null) {
            throw new NotFoundException(model: 'Sale', id: $id);
        }

        $saleDate = $sale->created_at->toDateString();

        $clientDailyPurchaseNumber = Sale::where('client_id', $sale->client_id)
            ->whereDate('created_at', $saleDate)
            ->where('id', '<=', $sale->id)
            ->count();

        $products = $sale->saleProducts->map(fn (SaleProduct $saleProduct) => [
            'product_id' => $saleProduct->product_id,
            'name' => $saleProduct->product->name,
            'price' => $saleProduct->price,
            'quantity' => $saleProduct->quantity,
        ])->toArray();

        $services = $sale->saleServices->map(fn (SaleService $saleService) => [
            'service_id' => $saleService->service_id,
            'name' => $saleService->service->name,
            'price' => $saleService->price,
        ])->toArray();

        return new SaleEntity(
            id: $sale->id,
            clientId: $sale->client_id,
            clientName: $sale->client->name,
            createdAt: $sale->created_at->toImmutable(),
            updatedAt: $sale->updated_at->toImmutable(),
            products: $products,
            services: $services,
            clientDailyPurchaseNumber: $clientDailyPurchaseNumber,
        );
    }

    /**
     * @param  SaleCreateProductItemDTO[]  $products
     * @param  SaleCreateServiceItemDTO[]  $services
     */
    public function createSale(int $clientId, array $products, array $services): void
    {
        DB::transaction(function () use ($clientId, $products, $services): void {
            $sale = Sale::create([
                'client_id' => $clientId,
            ]);

            $now = now();

            if ($products !== []) {
                SaleProduct::insert(array_map(fn (SaleCreateProductItemDTO $product) => [
                    'sale_id' => $sale->id,
                    'product_id' => $product->productId,
                    'price' => $product->price,
                    'quantity' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $products));

                ProductStockLedger::insert(array_map(fn (SaleCreateProductItemDTO $product) => [
                    'product_id' => $product->productId,
                    'quantity' => -1,
                    'reason' => 'sale',
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $products));

                $productIds = array_map(fn (SaleCreateProductItemDTO $p) => $p->productId, $products);

                Product::whereIn('id', $productIds)->decrement('stock_count');
            }

            if ($services !== []) {
                SaleService::insert(array_map(fn (SaleCreateServiceItemDTO $service) => [
                    'sale_id' => $sale->id,
                    'service_id' => $service->serviceId,
                    'price' => $service->price,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $services));
            }
        });
    }

    /**
     * @return int[]
     */
    public function getDistinctClientIdsForProductToday(int $productId): array
    {
        $today = now();

        return SaleProduct::query()
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->where('sale_products.product_id', $productId)
            ->whereBetween('sales.created_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->distinct()
            ->pluck('sales.client_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
    }

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
}

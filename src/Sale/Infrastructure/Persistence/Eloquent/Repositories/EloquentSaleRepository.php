<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Repositories;

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
        return SaleProduct::query()
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->where('sale_products.product_id', $productId)
            ->whereDate('sales.created_at', now()->toDateString())
            ->distinct()
            ->pluck('sales.client_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
    }
}

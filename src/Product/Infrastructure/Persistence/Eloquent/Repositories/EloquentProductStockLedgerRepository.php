<?php

namespace Module\Product\Infrastructure\Persistence\Eloquent\Repositories;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\ProductStockLedger;

class EloquentProductStockLedgerRepository implements ProductStockLedgerRepositoryContract
{
    public function getStockSumByProductID(int $productId): int
    {
        return (int) (ProductStockLedger::query()
            ->where('product_id', $productId)
            ->sum('quantity'));
    }

    public function getStockSumsByProductIDs(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        return ProductStockLedger::query()
            ->selectRaw('product_id, SUM(quantity) as stock_sum')
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id')
            ->pluck('stock_sum', 'product_id')
            ->mapWithKeys(fn ($stockSum, $productId) => [(int) $productId => (int) $stockSum])
            ->toArray();
    }
}

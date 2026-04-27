<?php

namespace Module\Product\Infrastructure\Persistence\Eloquent\Repositories;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Core\DTOs\RegisterStockDTO;
use Module\Product\Core\Entities\ProductStockLedgerEntity;
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

    public function create(RegisterStockDTO $dto): void
    {
        ProductStockLedger::create([
            'product_id' => $dto->productId,
            'reason' => $dto->reason->value,
            'quantity' => $dto->quantity,
        ]);
    }

    /**
     * @return ProductStockLedgerEntity[]
     */
    public function getByProductID(int $productId): array
    {
        return ProductStockLedger::query()
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ProductStockLedger $ledger) => new ProductStockLedgerEntity(
                id: $ledger->id,
                productId: $ledger->product_id,
                reason: $ledger->reason,
                quantity: $ledger->quantity,
                createdAt: $ledger->created_at->toIso8601String(),
            ))
            ->all();
    }
}

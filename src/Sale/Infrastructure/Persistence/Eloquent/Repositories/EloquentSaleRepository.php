<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Repositories;

use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Core\Entities\SaleEntity;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;

class EloquentSaleRepository implements SaleRepositoryContract
{
    public function getAll(): array
    {
        return Sale::get()->map(fn (Sale $sale) => new SaleEntity(
            id: $sale->id,
            clientId: $sale->client_id,
            clientName: $sale->client->name,
            status: $sale->status,
            createdAt: $sale->created_at->toImmutable(),
            updatedAt: $sale->updated_at->toImmutable(),
        ))->toArray();
    }
}
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
            $sale->id,
            $sale->client_id,
            $sale->client->name,
            $sale->status,
            $sale->timestamp,
        ))->toArray();
    }
}
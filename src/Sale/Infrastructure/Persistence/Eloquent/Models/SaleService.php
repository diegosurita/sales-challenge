<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\SaleServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

#[Fillable(['service_id', 'sale_id', 'price'])]
class SaleService extends Model
{
    /** @use HasFactory<SaleServiceFactory> */
    use HasFactory;

    /**
     * Get a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SaleServiceFactory::new();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
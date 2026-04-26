<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\SaleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;

#[Fillable(['client_id'])]
class Sale extends Model
{
    /** @use HasFactory<SaleFactory> */
    use HasFactory;

    /**
     * Get a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SaleFactory::new();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function saleProducts(): HasMany
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function saleServices(): HasMany
    {
        return $this->hasMany(SaleService::class);
    }
}

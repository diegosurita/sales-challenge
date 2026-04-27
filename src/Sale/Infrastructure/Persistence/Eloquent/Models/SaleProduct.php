<?php

namespace Module\Sale\Infrastructure\Persistence\Eloquent\Models;

use Database\Factories\SaleProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;

#[Fillable(['product_id', 'sale_id', 'price', 'quantity'])]
class SaleProduct extends Model
{
    /** @use HasFactory<SaleProductFactory> */
    use HasFactory;

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
    ];

    /**
     * Get a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SaleProductFactory::new();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}

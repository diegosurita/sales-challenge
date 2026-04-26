<?php

namespace Module\Product\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['product_id', 'reason', 'quantity'])]
#[Table('products_stock_ledger')]
class ProductStockLedger extends Model
{
}

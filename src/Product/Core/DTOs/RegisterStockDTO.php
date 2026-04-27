<?php

namespace Module\Product\Core\DTOs;

use Module\Product\Core\Enums\StockLedgerReason;

final class RegisterStockDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly StockLedgerReason $reason,
        public readonly int $quantity,
    ) {
    }
}

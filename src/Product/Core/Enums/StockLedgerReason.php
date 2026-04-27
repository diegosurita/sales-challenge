<?php

namespace Module\Product\Core\Enums;

enum StockLedgerReason: string
{
    case OPENING_BALANCE = 'opening_balance';
    case SALE = 'sale';
    case CORRECTION = 'correction';
    case PURCHASE_RECEIVED = 'purchase_received';
}

<?php

namespace Module\Sale\Core\Exceptions;

class InsufficientStockException extends \Exception
{
    public function __construct(string $productName)
    {
        parent::__construct("Insufficient stock for product \"{$productName}\".");
    }
}

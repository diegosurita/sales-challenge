<?php

namespace Module\Sale\Core\Exceptions;

class ProductClientLimitExceededException extends \Exception
{
    public function __construct(string $productName)
    {
        parent::__construct("Product \"{$productName}\" has already been sold to 3 different clients today.");
    }
}

<?php

namespace Module\Sale\Core\Exceptions;

class ServiceRequiredProductOutOfStockException extends \Exception
{
    public function __construct(string $serviceName, string $productName)
    {
        parent::__construct("The service \"{$serviceName}\" requires product \"{$productName}\" which is out of stock.");
    }
}

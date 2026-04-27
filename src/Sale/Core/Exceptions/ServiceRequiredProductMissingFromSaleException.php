<?php

namespace Module\Sale\Core\Exceptions;

class ServiceRequiredProductMissingFromSaleException extends \Exception
{
    public function __construct(string $serviceName, string $productName)
    {
        parent::__construct("The service \"{$serviceName}\" requires product \"{$productName}\" to be included in the sale.");
    }
}

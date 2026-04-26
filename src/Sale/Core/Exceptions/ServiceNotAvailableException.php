<?php

namespace Module\Sale\Core\Exceptions;

class ServiceNotAvailableException extends \Exception
{
    public function __construct(string $serviceName)
    {
        parent::__construct("Service \"{$serviceName}\" is not available.");
    }
}

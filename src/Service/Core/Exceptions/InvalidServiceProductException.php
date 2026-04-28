<?php

namespace Module\Service\Core\Exceptions;

use RuntimeException;

class InvalidServiceProductException extends RuntimeException
{
    public function __construct(int $productId)
    {
        parent::__construct("The selected product ({$productId}) does not exist.");
    }
}

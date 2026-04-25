<?php

namespace Module\Shared\Core\Exceptions;

class NotFoundException extends \Exception
{
    public function __construct(string $model, int $id)
    {
        parent::__construct("{$model} with ID {$id} not found");
    }
}

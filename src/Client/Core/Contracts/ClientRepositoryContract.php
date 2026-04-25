<?php

namespace Module\Client\Core\Contracts;

use Module\Client\Core\Entities\ClientEntity;

interface ClientRepositoryContract
{
    /**
     * @return ClientEntity[]
     */
    public function getAll(): array;
}
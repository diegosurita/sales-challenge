<?php

namespace Module\Client\Core\Contracts;

use Module\Client\Core\Entities\ClientEntity;
use Module\Client\Core\DTOs\NewUserDTO;

interface ClientRepositoryContract
{
    /**
     * @return ClientEntity[]
     */
    public function getAll(): array;

    public function createUser(NewUserDTO $dto): void;
}
<?php

namespace Module\Client\Core\UseCases;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\Entities\ClientEntity;

class GetClientByIDUseCase
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {
    }

    public function execute(int $clientId): ClientEntity
    {
        return $this->clientRepository->getByID($clientId);
    }
}

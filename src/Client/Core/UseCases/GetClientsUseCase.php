<?php

namespace Module\Client\Core\UseCases;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\Entities\ClientEntity;

class GetClientsUseCase
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {
    }

    /**
     * @return ClientEntity[]
     */
    public function execute(): array
    {
        return $this->clientRepository->getAll();
    }
}
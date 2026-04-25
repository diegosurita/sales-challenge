<?php

namespace Module\Client\Core\UseCases;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\DTOs\ClientFormDTO;

class UpdateClientUseCase
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {
    }

    public function execute(ClientFormDTO $dto): void
    {
        $this->clientRepository->updateClient($dto);
    }
}
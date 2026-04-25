<?php

namespace Module\Client\Core\UseCases;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\DTOs\NewUserDTO;

class CreateClientUseCase
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {
    }

    public function execute(NewUserDTO $dto): void
    {
        $this->clientRepository->createUser($dto);
    }
}
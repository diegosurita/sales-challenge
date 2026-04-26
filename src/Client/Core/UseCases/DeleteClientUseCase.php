<?php

namespace Module\Client\Core\UseCases;

use Module\Client\Core\Contracts\ClientRepositoryContract;

class DeleteClientUseCase
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {}

    public function execute(int $id): void
    {
        $this->clientRepository->delete($id);
    }
}
<?php

namespace Module\Client\Core\Contracts;

use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\Entities\ClientEntity;

interface ClientRepositoryContract
{
    /**
     * @return ClientEntity[]
     */
    public function getAll(): array;

    public function createUser(ClientFormDTO $dto): void;

    public function getByID(int $id): ClientEntity;

    public function updateClient(ClientFormDTO $dto): void;

    public function delete(int $id): void;

    /**
     * @return array<int, array{name: string, total_sales_value: float}>
     */
    public function getTopClientsBySalesValue(int $limit): array;
}

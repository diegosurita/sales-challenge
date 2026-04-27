<?php

namespace Module\Service\Core\Contracts;

use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\Entities\ServiceEntity;

interface ServiceRepositoryContract
{
    /**
     * @return ServiceEntity[]
     */
    public function getAll(): array;

    public function createService(ServiceFormDTO $dto): void;

    public function getByID(int $id): ServiceEntity;

    /**
     * @param  int[]  $ids
     * @return array<int, ServiceEntity>
     */
    public function getManyByIDs(array $ids): array;

    public function updateService(ServiceFormDTO $dto): void;

    public function delete(int $id): void;

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingServices(int $limit): array;
}

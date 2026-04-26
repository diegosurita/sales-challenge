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

    public function updateService(ServiceFormDTO $dto): void;

    public function delete(int $id): void;
}

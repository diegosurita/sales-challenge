<?php

namespace Module\Service\Core\UseCases;

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\Entities\ServiceEntity;

class GetServiceByIDUseCase
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    public function execute(int $serviceId): ServiceEntity
    {
        return $this->serviceRepository->getByID($serviceId);
    }
}

<?php

namespace Module\Service\Core\UseCases;

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\Entities\ServiceEntity;

class GetServicesUseCase
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    /**
     * @return ServiceEntity[]
     */
    public function execute(): array
    {
        return $this->serviceRepository->getAll();
    }
}

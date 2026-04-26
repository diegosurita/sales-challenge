<?php

namespace Module\Service\Core\UseCases;

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\Entities\ServiceEntity;

class GetServicesByIDsUseCase
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    /**
     * @param  int[]  $ids
     * @return array<int, ServiceEntity>
     */
    public function execute(array $ids): array
    {
        return $this->serviceRepository->getManyByIDs($ids);
    }
}

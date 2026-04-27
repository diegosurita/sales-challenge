<?php

namespace Module\Service\Infrastructure\Gateways;

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Shared\Core\Contracts\ServiceModuleGatewayContract;

class ServiceModuleGateway implements ServiceModuleGatewayContract
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingServices(int $limit): array
    {
        return $this->serviceRepository->getTopSellingServices(limit: $limit);
    }
}

<?php

namespace Module\Service\Infrastructure\Gateways;

use Module\Service\Core\UseCases\GetServiceByIDUseCase;
use Module\Service\Core\UseCases\GetServicesByIDsUseCase;
use Module\Shared\Core\Contracts\ServiceQueryServiceContract;
use Module\Shared\Core\Exceptions\NotFoundException;

class ServiceQueryGateway implements ServiceQueryServiceContract
{
    public function __construct(
        private readonly GetServiceByIDUseCase $getServiceByIDUseCase,
        private readonly GetServicesByIDsUseCase $getServicesByIDsUseCase,
    ) {}

    /**
     * @return array{id: int, name: string, price: float, available: bool, product: array{id: int, name: string}|null}|null
     */
    public function getServiceInfo(int $serviceId): ?array
    {
        try {
            return $this->getServiceByIDUseCase->execute($serviceId)->toArray();
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, available: bool, product: array{id: int, name: string}|null}>
     */
    public function getServicesInfo(array $ids): array
    {
        $entities = $this->getServicesByIDsUseCase->execute($ids);

        return array_map(fn ($entity) => $entity->toArray(), $entities);
    }
}

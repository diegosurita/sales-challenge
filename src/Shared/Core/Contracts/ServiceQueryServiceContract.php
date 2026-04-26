<?php

namespace Module\Shared\Core\Contracts;

interface ServiceQueryServiceContract
{
    /**
     * @return array{id: int, name: string, price: float, available: bool}|null
     */
    public function getServiceInfo(int $serviceId): ?array;

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, available: bool}>
     */
    public function getServicesInfo(array $ids): array;
}

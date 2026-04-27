<?php

namespace Module\Shared\Core\Contracts;

interface ServiceQueryServiceContract
{
    /**
     * @return array{id: int, name: string, price: float, available: bool, product: array{id: int, name: string}|null}|null
     */
    public function getServiceInfo(int $serviceId): ?array;

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, available: bool, product: array{id: int, name: string}|null}>
     */
    public function getServicesInfo(array $ids): array;
}

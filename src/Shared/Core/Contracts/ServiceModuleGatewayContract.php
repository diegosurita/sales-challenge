<?php

namespace Module\Shared\Core\Contracts;

interface ServiceModuleGatewayContract
{
    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingServices(int $limit): array;
}

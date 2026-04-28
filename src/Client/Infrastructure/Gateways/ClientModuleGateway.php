<?php

namespace Module\Client\Infrastructure\Gateways;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Shared\Core\Contracts\ClientModuleGatewayContract;

class ClientModuleGateway implements ClientModuleGatewayContract
{
    public function __construct(
        private readonly ClientRepositoryContract $clientRepository,
    ) {}

    /**
     * @return array<int, array{name: string, total_sales_value: float}>
     */
    public function getTopClientsBySalesValue(int $limit): array
    {
        return $this->clientRepository->getTopClientsBySalesValue(limit: $limit);
    }
}

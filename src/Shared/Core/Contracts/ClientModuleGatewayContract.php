<?php

namespace Module\Shared\Core\Contracts;

interface ClientModuleGatewayContract
{
    /**
     * @return array<int, array{name: string, total_sales_value: float}>
     */
    public function getTopClientsBySalesValue(int $limit): array;
}

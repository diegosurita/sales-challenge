<?php

namespace Module\Shared\Core\Contracts;

interface ProductQueryServiceContract
{
    /**
     * @return array{id: int, name: string, price: float, stock_count: int|null}|null
     */
    public function getProductInfo(int $productId): ?array;

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, stock_count: int|null}>
     */
    public function getProductsInfo(array $ids): array;
}

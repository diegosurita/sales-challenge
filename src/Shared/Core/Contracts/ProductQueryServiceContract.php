<?php

namespace Module\Shared\Core\Contracts;

interface ProductQueryServiceContract
{
    /**
     * @return array<int, array{id: int, name: string}>
     */
    public function getProducts(): array;

    /**
     * @return array{id: int, name: string, price: float, stock_count: int}|null
     */
    public function getProductInfo(int $productId): ?array;

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, stock_count: int}>
     */
    public function getProductsInfo(array $ids): array;
}

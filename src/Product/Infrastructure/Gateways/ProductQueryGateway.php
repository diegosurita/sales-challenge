<?php

namespace Module\Product\Infrastructure\Gateways;

use Module\Product\Core\UseCases\GetProductByIDUseCase;
use Module\Product\Core\UseCases\GetProductsByIDsUseCase;
use Module\Shared\Core\Contracts\ProductQueryServiceContract;
use Module\Shared\Core\Exceptions\NotFoundException;

class ProductQueryGateway implements ProductQueryServiceContract
{
    public function __construct(
        private readonly GetProductByIDUseCase $getProductByIDUseCase,
        private readonly GetProductsByIDsUseCase $getProductsByIDsUseCase,
    ) {}

    /**
     * @return array{id: int, name: string, price: float, stock_count: int|null}|null
     */
    public function getProductInfo(int $productId): ?array
    {
        try {
            return $this->getProductByIDUseCase->execute($productId)->toArray();
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * @param  int[]  $ids
     * @return array<int, array{id: int, name: string, price: float, stock_count: int|null}>
     */
    public function getProductsInfo(array $ids): array
    {
        $entities = $this->getProductsByIDsUseCase->execute($ids);

        return array_map(fn ($entity) => $entity->toArray(), $entities);
    }
}

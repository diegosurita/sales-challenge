<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Core\DTOs\RegisterStockDTO;

class RegisterProductStockUseCase
{
    public function __construct(
        private readonly ProductStockLedgerRepositoryContract $repository,
        private readonly RecalculateProductStockUseCase $recalculateProductStockUseCase,
    ) {
    }

    public function execute(RegisterStockDTO $dto): void
    {
        $this->repository->create(dto: $dto);

        $this->recalculateProductStockUseCase->execute(productId: $dto->productId);
    }
}

<?php

namespace Module\Product\Core\UseCases;

use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\DTOs\RegisterStockDTO;
use Module\Product\Core\Enums\StockLedgerReason;

class CreateProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly RegisterProductStockUseCase $registerProductStockUseCase,
    ) {}

    public function execute(ProductFormDTO $dto): void
    {
        $productId = $this->productRepository->createProduct($dto);

        if ($dto->stockCount > 0) {
            $this->registerProductStockUseCase->execute(new RegisterStockDTO(
                productId: $productId,
                reason: StockLedgerReason::OPENING_BALANCE,
                quantity: $dto->stockCount,
            ));
        }
    }
}

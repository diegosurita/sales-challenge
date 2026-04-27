<?php

namespace Module\Sale\Core\UseCases;

use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Core\Entities\SaleEntity;

class GetSaleByIDUseCase
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
    ) {}

    public function execute(int $id): SaleEntity
    {
        return $this->saleRepository->getByID(id: $id);
    }
}

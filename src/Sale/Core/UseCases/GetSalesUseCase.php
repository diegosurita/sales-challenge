<?php

namespace Module\Sale\Core\UseCases;

use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Core\Entities\SaleEntity;

class GetSalesUseCase
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
    ) {}

    /**
     * @return SaleEntity[]
     */
    public function execute(): array
    {
        return $this->saleRepository->getAll();
    }
}
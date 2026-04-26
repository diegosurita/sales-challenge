<?php

namespace Module\Sale\Core\Contracts;

use Module\Sale\Core\Entities\SaleEntity;

interface SaleRepositoryContract
{
    /**
     * @return SaleEntity[]
     */
    public function getAll(): array;
}
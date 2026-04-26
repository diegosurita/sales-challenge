<?php

namespace Module\Service\Core\UseCases;

use Module\Service\Core\Contracts\ServiceRepositoryContract;

class DeleteServiceUseCase
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    public function execute(int $id): void
    {
        $this->serviceRepository->delete($id);
    }
}

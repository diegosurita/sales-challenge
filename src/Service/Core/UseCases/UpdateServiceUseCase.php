<?php

namespace Module\Service\Core\UseCases;

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\DTOs\ServiceFormDTO;

class UpdateServiceUseCase
{
    public function __construct(
        private readonly ServiceRepositoryContract $serviceRepository,
    ) {}

    public function execute(ServiceFormDTO $dto): void
    {
        $this->serviceRepository->updateService($dto);
    }
}

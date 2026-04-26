<?php

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\UseCases\CreateServiceUseCase;

it('should create a service via repository', function () {
    $dto = new ServiceFormDTO(
        name: 'New Service',
        price: 150,
        available: true,
    );

    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('createService')->once()->with($dto);

    $useCase = new CreateServiceUseCase($repository);

    $useCase->execute($dto);
});

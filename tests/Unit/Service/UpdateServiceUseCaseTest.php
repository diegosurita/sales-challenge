<?php

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\UseCases\UpdateServiceUseCase;

it('should update a service via repository', function () {
    $dto = new ServiceFormDTO(
        name: 'Updated Service',
        price: 250,
        id: 1,
    );

    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('updateService')->once()->with($dto);

    $useCase = new UpdateServiceUseCase($repository);

    $useCase->execute($dto);
});

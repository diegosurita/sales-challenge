<?php

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\UseCases\UpdateClientUseCase;

it('should update a client via repository', function () {
    $dto = new ClientFormDTO('Updated Name', 1);

    $repository = mock(ClientRepositoryContract::class);
    $repository->shouldReceive('updateClient')->once()->with($dto);

    $useCase = new UpdateClientUseCase($repository);

    $useCase->execute($dto);
});
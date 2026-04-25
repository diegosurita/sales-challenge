<?php

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\UseCases\CreateClientUseCase;
use Module\Client\Core\DTOs\NewUserDTO;

it('should create a client via repository', function () {
    $dto = new NewUserDTO('New Client');

    $repository = mock(ClientRepositoryContract::class);
    $repository->shouldReceive('createUser')->once()->with($dto);

    $useCase = new CreateClientUseCase($repository);

    $useCase->execute($dto);
});
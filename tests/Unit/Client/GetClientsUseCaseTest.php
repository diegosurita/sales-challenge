<?php

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\UseCases\GetClientsUseCase;
use Module\Client\Core\Entities\ClientEntity;

it('should return all clients from repository', function () {
    $clients = [
        new ClientEntity(1, 'Client 1'),
        new ClientEntity(2, 'Client 2'),
    ];

    $repository = mock(ClientRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($clients);

    $useCase = new GetClientsUseCase($repository);

    $result = $useCase->execute();

    expect($result)->toBe($clients);
});
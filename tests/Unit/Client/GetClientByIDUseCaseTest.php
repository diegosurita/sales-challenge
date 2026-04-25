<?php

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\Entities\ClientEntity;
use Module\Client\Core\UseCases\GetClientByIDUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should return client when found', function () {
    $client = new ClientEntity(1, 'Test Client');

    $repository = mock(ClientRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(1)->once()->andReturn($client);

    $useCase = new GetClientByIDUseCase($repository);

    $result = $useCase->execute(1);

    expect($result)->toBe($client);
});

it('should throw NotFoundException when client not found', function () {
    $repository = mock(ClientRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(999)->once()->andThrow(new NotFoundException('Client', 999));

    $useCase = new GetClientByIDUseCase($repository);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});

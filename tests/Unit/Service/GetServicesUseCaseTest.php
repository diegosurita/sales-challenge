<?php

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\Entities\ServiceEntity;
use Module\Service\Core\UseCases\GetServicesUseCase;

it('should return all services from repository', function () {
    $services = [
        new ServiceEntity(id: 1, name: 'Service 1', price: 10),
        new ServiceEntity(id: 2, name: 'Service 2', price: 20),
    ];

    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('getAll')->once()->andReturn($services);

    $useCase = new GetServicesUseCase($repository);

    $result = $useCase->execute();

    expect($result)->toBe($services);
});

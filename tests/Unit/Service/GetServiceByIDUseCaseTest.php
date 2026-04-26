<?php

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\Entities\ServiceEntity;
use Module\Service\Core\UseCases\GetServiceByIDUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should return service when found', function () {
    $service = new ServiceEntity(id: 1, name: 'Test Service', price: 100);

    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(1)->once()->andReturn($service);

    $useCase = new GetServiceByIDUseCase($repository);

    $result = $useCase->execute(1);

    expect($result)->toBe($service);
});

it('should throw NotFoundException when service not found', function () {
    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('getByID')->with(999)->once()->andThrow(new NotFoundException('Service', 999));

    $useCase = new GetServiceByIDUseCase($repository);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});

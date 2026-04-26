<?php

use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\UseCases\DeleteServiceUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

it('should delete a service via repository', function () {
    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('delete')->once()->with(1);

    $useCase = new DeleteServiceUseCase($repository);

    $useCase->execute(1);
});

it('should throw NotFoundException when deleting a non-existent service', function () {
    $repository = mock(ServiceRepositoryContract::class);
    $repository->shouldReceive('delete')->with(999)->once()->andThrow(new NotFoundException('Service', 999));

    $useCase = new DeleteServiceUseCase($repository);

    expect(fn () => $useCase->execute(999))->toThrow(NotFoundException::class);
});

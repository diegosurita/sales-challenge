<?php

use Module\Auth\Core\Contracts\UserAuthenticationServiceContract;
use Module\Auth\Core\UseCases\AuthenticateUserUseCase;

it('should delegate authentication to the service with the provided credentials', function () {
    $service = mock(UserAuthenticationServiceContract::class);
    $service->shouldReceive('authenticate')->once()->with('john@example.com', 'secret-password')->andReturn(true);

    $useCase = new AuthenticateUserUseCase($service);

    $result = $useCase->execute('john@example.com', 'secret-password');

    expect($result)->toBeTrue();
});

it('should return the boolean result from the authentication service', function (bool $expectedResult) {
    $service = mock(UserAuthenticationServiceContract::class);
    $service->shouldReceive('authenticate')->andReturn($expectedResult);

    $useCase = new AuthenticateUserUseCase($service);

    $result = $useCase->execute('john@example.com', 'secret-password');

    expect($result)->toBe($expectedResult);
})->with([true, false]);

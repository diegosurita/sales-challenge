<?php

namespace Module\Auth\Core\UseCases;

use Module\Auth\Core\Contracts\UserAuthenticationServiceContract;

class AuthenticateUserUseCase
{
    public function __construct(
        private readonly UserAuthenticationServiceContract $userAuthenticationService,
    ) {
    }

    public function execute(string $email, string $password): bool
    {
        return $this->userAuthenticationService->authenticate($email, $password);
    }
}

<?php

namespace Module\Auth\Core\UseCases;

use Module\Auth\Core\Contracts\UserAuthenticationServiceContract;

class LogoutUserUseCase
{
    public function __construct(
        private readonly UserAuthenticationServiceContract $userAuthenticationService,
    ) {
    }

    public function execute(): void
    {
        $this->userAuthenticationService->logout();
    }
}

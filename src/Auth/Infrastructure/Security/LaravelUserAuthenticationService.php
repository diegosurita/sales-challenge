<?php

namespace Module\Auth\Infrastructure\Security;

use Illuminate\Support\Facades\Auth;
use Module\Auth\Core\Contracts\UserAuthenticationServiceContract;

class LaravelUserAuthenticationService implements UserAuthenticationServiceContract
{
    public function authenticate(string $email, string $password): bool
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }
}

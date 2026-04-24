<?php

namespace Module\Auth\Core\Contracts;

interface UserAuthenticationServiceContract
{
    public function authenticate(string $email, string $password): bool;
}

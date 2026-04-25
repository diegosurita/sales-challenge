<?php

namespace Module\Client\Core\DTOs;

final class NewUserDTO
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
<?php

namespace Module\Client\Core\DTOs;

final class ClientFormDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $id = null,
    ) {}
}

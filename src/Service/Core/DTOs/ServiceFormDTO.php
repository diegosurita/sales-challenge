<?php

namespace Module\Service\Core\DTOs;

final class ServiceFormDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?int $id = null,
    ) {}
}

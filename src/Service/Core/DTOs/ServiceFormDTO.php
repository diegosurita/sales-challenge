<?php

namespace Module\Service\Core\DTOs;

final class ServiceFormDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly bool $available = true,
        public readonly ?int $id = null,
        public readonly ?int $productId = null,
    ) {}
}

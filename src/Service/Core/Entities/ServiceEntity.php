<?php

namespace Module\Service\Core\Entities;

final class ServiceEntity
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly float $price,
        private readonly bool $available,
        private readonly ?ProductEntity $product = null,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getAvailable(): bool
    {
        return $this->available;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'available' => $this->available,
            'product' => $this->product ? [
                'id' => $this->product->getId(),
                'name' => $this->product->getName(),
            ] : null,
        ];
    }
}

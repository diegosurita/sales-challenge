<?php

namespace Module\Product\Core\Entities;

final class ProductEntity
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly float $price,
        private ?int $stockCount = null,
    ) {
    }

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

    public function getStockCount(): ?int
    {
        return $this->stockCount;
    }

    public function setCount(int $count): self
    {
        $this->stockCount = $count;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock_count' => $this->stockCount,
        ];
    }
}
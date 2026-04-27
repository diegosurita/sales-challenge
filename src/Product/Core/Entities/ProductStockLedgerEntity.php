<?php

namespace Module\Product\Core\Entities;

final class ProductStockLedgerEntity
{
    public function __construct(
        private readonly int $id,
        private readonly int $productId,
        private readonly string $reason,
        private readonly int $quantity,
        private readonly string $createdAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'reason' => $this->reason,
            'quantity' => $this->quantity,
            'created_at' => $this->createdAt,
        ];
    }
}

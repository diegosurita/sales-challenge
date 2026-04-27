<?php

namespace Module\Sale\Core\Entities;

final class SaleEntity
{
    public function __construct(
        private readonly int $id,
        private readonly int $clientId,
        private readonly string $clientName,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt,
        private readonly ?array $products = null,
        private readonly ?array $services = null,
        private readonly ?int $clientDailyPurchaseNumber = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function getServices(): ?array
    {
        return $this->services;
    }

    public function getClientDailyPurchaseNumber(): ?int
    {
        return $this->clientDailyPurchaseNumber;
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'updated_at' => $this->updatedAt->format(\DateTimeInterface::ATOM),
        ];

        if ($this->products !== null) {
            $data['products'] = $this->products;
        }

        if ($this->services !== null) {
            $data['services'] = $this->services;
        }

        if ($this->clientDailyPurchaseNumber !== null) {
            $data['client_daily_purchase_number'] = $this->clientDailyPurchaseNumber;
        }

        return $data;
    }
}
<?php

namespace Module\Sale\Core\Entities;

final class SaleEntity
{
    public function __construct(
        private readonly int $id,
        private readonly int $clientId,
        private readonly string $clientName,
        private readonly string $status,
        private readonly string $timestamp,
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->clientId,
            'client_name' => $this->clientName,
            'status' => $this->status,
            'timestamp' => $this->timestamp,
        ];
    }
}
<?php

namespace Module\Client\Infrastructure\Persistence\Eloquent\Repositories;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\Entities\ClientEntity;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Shared\Core\Exceptions\NotFoundException;

class EloquentClientRepository implements ClientRepositoryContract
{
    public function getAll(): array
    {
        return Client::all()->map(fn (Client $client) => new ClientEntity(
            $client->id,
            $client->name,
        ))->toArray();
    }

    public function createUser(ClientFormDTO $dto): void
    {
        Client::create([
            'name' => $dto->name,
        ]);
    }

    public function getByID(int $id): ClientEntity
    {
        $client = Client::find($id);

        if (!$client) {
            throw new NotFoundException('Client', $id);
        }

        return new ClientEntity(
            $client->id,
            $client->name,
        );
    }

    public function updateClient(ClientFormDTO $dto): void
    {
        $client = Client::find($dto->id);

        if (!$client) {
            throw new NotFoundException('Client', $dto->id);
        }

        $client->update([
            'name' => $dto->name,
        ]);
    }
}

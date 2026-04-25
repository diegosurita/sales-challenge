<?php

namespace Module\Client\Infrastructure\Persistence\Eloquent\Repositories;

use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\Entities\ClientEntity;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Client\Core\DTOs\NewUserDTO;

class EloquentClientRepository implements ClientRepositoryContract
{
    public function getAll(): array
    {
        return Client::all()->map(fn (Client $client) => new ClientEntity(
            $client->id,
            $client->name,
        ))->toArray();
    }

    public function createUser(NewUserDTO $dto): void
    {
        Client::create([
            'name' => $dto->name,
        ]);
    }
}
<?php

use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\UseCases\UpdateClientUseCase;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;

it('should update a client via repository', function () {
    // Create a client first
    $client = Client::factory()->create(['name' => 'Original Name']);

    $useCase = app(UpdateClientUseCase::class);
    $dto = new ClientFormDTO('Updated Name', $client->id);

    $useCase->execute($dto);

    $client->refresh();
    expect($client->name)->toBe('Updated Name');
});
<?php

namespace Module\Client\Interface\Controllers;

use App\Http\Controllers\Controller;
use Module\Client\Core\UseCases\GetClientsUseCase;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(GetClientsUseCase $useCase)
    {
        $clients = $useCase->execute();

        return Inertia::render('ClientsList', [
            'clients' => array_map(fn ($client) => $client->toArray(), $clients),
        ]);
    }
}
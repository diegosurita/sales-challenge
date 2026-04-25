<?php

namespace Module\Client\Interface\Controllers;

use App\Http\Controllers\Controller;
use Module\Client\Core\UseCases\GetClientsUseCase;
use Module\Client\Core\UseCases\CreateClientUseCase;
use Module\Client\Core\DTOs\NewUserDTO;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(GetClientsUseCase $useCase)
    {
        $clients = $useCase->execute();

        return Inertia::render('Client/ClientsList', [
            'clients' => array_map(fn ($client) => $client->toArray(), $clients),
        ]);
    }

    public function create()
    {
        return Inertia::render('Client/ClientForm');
    }

    public function store(Request $request, CreateClientUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $dto = new NewUserDTO($request->name);

        $useCase->execute($dto);

        return redirect()->route('clients.index');
    }
}
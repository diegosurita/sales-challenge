<?php

namespace Module\Client\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\UseCases\CreateClientUseCase;
use Module\Client\Core\UseCases\DeleteClientUseCase;
use Module\Client\Core\UseCases\GetClientByIDUseCase;
use Module\Client\Core\UseCases\GetClientsUseCase;
use Module\Client\Core\UseCases\UpdateClientUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

class ClientController extends Controller
{
    public function index(GetClientsUseCase $useCase)
    {
        $clients = $useCase->execute();

        return Inertia::render('Client/ClientsList', [
            'clients' => array_map(fn ($client) => $client->toArray(), $clients),
            'successMessage' => session('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Client/ClientForm');
    }

    public function edit(int $id, GetClientByIDUseCase $useCase)
    {
        try {
            $client = $useCase->execute($id);

            return Inertia::render('Client/ClientForm', [
                'client' => $client->toArray(),
            ]);
        } catch (NotFoundException $e) {
            abort(404);
        }
    }

    public function store(Request $request, CreateClientUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $dto = new ClientFormDTO($request->name);

        $useCase->execute($dto);

        session()->flash('success', 'Client created successfully.');

        return redirect()->route('clients.index');
    }

    public function update(Request $request, int $id, UpdateClientUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $dto = new ClientFormDTO($request->name, $id);

        $useCase->execute($dto);

        session()->flash('success', 'Client updated successfully.');

        return redirect()->route('clients.index');
    }

    public function destroy(int $id, DeleteClientUseCase $useCase)
    {
        try {
            $useCase->execute($id);

            return response()->noContent();
        } catch (NotFoundException $e) {
            abort(404);
        }
    }
}

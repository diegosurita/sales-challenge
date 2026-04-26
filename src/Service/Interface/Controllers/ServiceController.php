<?php

namespace Module\Service\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\UseCases\CreateServiceUseCase;
use Module\Service\Core\UseCases\DeleteServiceUseCase;
use Module\Service\Core\UseCases\GetServiceByIDUseCase;
use Module\Service\Core\UseCases\GetServicesUseCase;
use Module\Service\Core\UseCases\UpdateServiceUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

class ServiceController extends Controller
{
    public function index(GetServicesUseCase $useCase)
    {
        $services = $useCase->execute();

        return Inertia::render('Service/ServicesList', [
            'services' => array_map(fn ($service) => $service->toArray(), $services),
            'successMessage' => session('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Service/ServiceForm');
    }

    public function edit(int $id, GetServiceByIDUseCase $useCase)
    {
        try {
            $service = $useCase->execute($id);

            return Inertia::render('Service/ServiceForm', [
                'service' => $service->toArray(),
            ]);
        } catch (NotFoundException $e) {
            abort(404);
        }
    }

    public function store(Request $request, CreateServiceUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'available' => 'boolean',
        ]);

        $dto = new ServiceFormDTO(
            name: $request->name,
            price: (float) $request->price,
            available: $request->boolean('available', true),
        );

        $useCase->execute($dto);

        session()->flash('success', 'Service created successfully.');

        return redirect()->route('services.index');
    }

    public function update(Request $request, int $id, UpdateServiceUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'available' => 'boolean',
        ]);

        $dto = new ServiceFormDTO(
            name: $request->name,
            price: (float) $request->price,
            available: $request->boolean('available', true),
            id: $id,
        );

        $useCase->execute($dto);

        session()->flash('success', 'Service updated successfully.');

        return redirect()->route('services.index');
    }

    public function destroy(int $id, DeleteServiceUseCase $useCase)
    {
        try {
            $useCase->execute($id);

            return response()->noContent();
        } catch (NotFoundException $e) {
            abort(404);
        }
    }
}

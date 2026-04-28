<?php

namespace Module\Service\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\Exceptions\InvalidServiceProductException;
use Module\Service\Core\UseCases\CreateServiceUseCase;
use Module\Service\Core\UseCases\DeleteServiceUseCase;
use Module\Service\Core\UseCases\GetServiceByIDUseCase;
use Module\Service\Core\UseCases\GetServicesUseCase;
use Module\Service\Core\UseCases\UpdateServiceUseCase;
use Module\Shared\Core\Contracts\ProductQueryServiceContract;
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

    public function create(ProductQueryServiceContract $productQueryService)
    {
        return Inertia::render('Service/ServiceForm', [
            'products' => $productQueryService->getProducts(),
        ]);
    }

    public function edit(int $id, GetServiceByIDUseCase $useCase, ProductQueryServiceContract $productQueryService)
    {
        try {
            $service = $useCase->execute($id);

            return Inertia::render('Service/ServiceForm', [
                'service' => $service->toArray(),
                'products' => $productQueryService->getProducts(),
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
            'product_id' => 'nullable|integer',
        ]);

        $dto = new ServiceFormDTO(
            name: $request->name,
            price: (float) $request->price,
            available: $request->boolean('available', true),
            productId: $request->filled('product_id') ? (int) $request->product_id : null,
        );

        try {
            $useCase->execute($dto);
        } catch (InvalidServiceProductException $e) {
            return back()->withErrors(['product_id' => $e->getMessage()])->withInput();
        }

        session()->flash('success', 'Service created successfully.');

        return redirect()->route('services.index');
    }

    public function update(Request $request, int $id, UpdateServiceUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'available' => 'boolean',
            'product_id' => 'nullable|integer',
        ]);

        $dto = new ServiceFormDTO(
            name: $request->name,
            price: (float) $request->price,
            available: $request->boolean('available', true),
            id: $id,
            productId: $request->filled('product_id') ? (int) $request->product_id : null,
        );

        try {
            $useCase->execute($dto);
        } catch (InvalidServiceProductException $e) {
            return back()->withErrors(['product_id' => $e->getMessage()])->withInput();
        }

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

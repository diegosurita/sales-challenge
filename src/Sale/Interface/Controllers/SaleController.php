<?php

namespace Module\Sale\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Module\Client\Core\UseCases\GetClientsUseCase;
use Module\Product\Core\UseCases\GetProductsUseCase;
use Module\Sale\Core\DTOs\SaleFormDTO;
use Module\Sale\Core\DTOs\SaleFormProductItemDTO;
use Module\Sale\Core\DTOs\SaleFormServiceItemDTO;
use Module\Sale\Core\Exceptions\InsufficientStockException;
use Module\Sale\Core\Exceptions\ProductClientLimitExceededException;
use Module\Sale\Core\Exceptions\ServiceNotAvailableException;
use Module\Sale\Core\Exceptions\ServiceRequiredProductOutOfStockException;
use Module\Sale\Core\UseCases\CreateSaleUseCase;
use Module\Sale\Core\UseCases\GetSalesUseCase;
use Module\Service\Core\UseCases\GetServicesUseCase;

class SaleController extends Controller
{
    public function index(GetSalesUseCase $useCase): Response
    {
        $sales = $useCase->execute();

        return Inertia::render('Sale/SalesList', [
            'sales' => array_map(fn ($sale) => $sale->toArray(), $sales),
            'successMessage' => session('success'),
        ]);
    }

    public function create(
        GetClientsUseCase $clientsUseCase,
        GetProductsUseCase $productsUseCase,
        GetServicesUseCase $servicesUseCase,
    ): Response {
        $products = array_filter(
            $productsUseCase->execute(),
            fn ($p) => $p->getStockCount() > 0,
        );

        $services = array_filter(
            $servicesUseCase->execute(),
            fn ($s) => $s->getAvailable(),
        );

        return Inertia::render('Sale/SaleForm', [
            'clients' => array_map(fn ($c) => $c->toArray(), $clientsUseCase->execute()),
            'products' => array_map(fn ($p) => $p->toArray(), array_values($products)),
            'services' => array_map(fn ($s) => $s->toArray(), array_values($services)),
        ]);
    }

    public function store(Request $request, CreateSaleUseCase $useCase): RedirectResponse
    {
        $request->validate([
            'client_id' => 'required|integer',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_with:products|integer',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required_with:services|integer',
        ]);

        $products = $request->input('products', []);
        $services = $request->input('services', []);

        if (empty($products) && empty($services)) {
            return back()
                ->withErrors(['items' => 'You must add at least one product or service.'])
                ->withInput();
        }

        $dto = new SaleFormDTO(
            clientId: (int) $request->input('client_id'),
            products: array_map(
                fn (array $item) => new SaleFormProductItemDTO(productId: (int) $item['product_id']),
                $products,
            ),
            services: array_map(
                fn (array $item) => new SaleFormServiceItemDTO(serviceId: (int) $item['service_id']),
                $services,
            ),
        );

        try {
            $useCase->execute($dto);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['products' => $e->getMessage()])->withInput();
        } catch (ProductClientLimitExceededException $e) {
            return back()->withErrors(['products' => $e->getMessage()])->withInput();
        } catch (ServiceNotAvailableException $e) {
            return back()->withErrors(['services' => $e->getMessage()])->withInput();
        } catch (ServiceRequiredProductOutOfStockException $e) {
            return back()->withErrors(['services' => $e->getMessage()])->withInput();
        }

        session()->flash('success', 'Sale created successfully.');

        return redirect()->route('sales.index');
    }
}

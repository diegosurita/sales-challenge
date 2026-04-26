<?php

namespace Module\Sale\Interface\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Module\Sale\Core\UseCases\GetSalesUseCase;

class SaleController extends Controller
{
    public function index(GetSalesUseCase $useCase)
    {
        $sales = $useCase->execute();

        return Inertia::render('Sale/SalesList', [
            'sales' => array_map(fn ($sale) => $sale->toArray(), $sales),
            'successMessage' => session('success'),
        ]);
    }
}
<?php

namespace Module\Shared\Interface\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Module\Shared\Core\UseCases\GetDashboardDataUseCase;

class DashboardController extends Controller
{
    public function index(GetDashboardDataUseCase $useCase): Response
    {
        $data = $useCase->execute();

        return Inertia::render('Shared/Dashboard', [
            'currentMonthRevenue' => $data->currentMonthRevenue,
            'monthlyRevenue' => $data->monthlyRevenue,
            'topProducts' => $data->topProducts,
            'topServices' => $data->topServices,
        ]);
    }
}

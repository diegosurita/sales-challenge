<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleService;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

class SaleServiceSeeder extends Seeder
{
    public function run(): void
    {
        $sales = Sale::all();
        $serviceIds = Service::pluck('id')->toArray();

        foreach ($sales as $sale) {
            $serviceCount = rand(0, 2);

            if ($serviceCount === 0) {
                continue;
            }

            $selectedServiceIds = fake()->randomElements($serviceIds, $serviceCount);

            foreach ($selectedServiceIds as $serviceId) {
                $service = Service::find($serviceId);

                SaleService::create([
                    'sale_id' => $sale->id,
                    'service_id' => $serviceId,
                    'price' => $service->price,
                ]);
            }
        }
    }
}
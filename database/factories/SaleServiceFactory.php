<?php

namespace Database\Factories;

use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;

/**
 * @extends Factory<SaleService>
 */
class SaleServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = SaleService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'sale_id' => Sale::factory(),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 89, max: 499),
        ];
    }
}
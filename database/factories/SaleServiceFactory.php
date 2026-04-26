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
            'service_id' => Service::factory()->create()->id,
            'sale_id' => Sale::factory()->create()->id,
            'price' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
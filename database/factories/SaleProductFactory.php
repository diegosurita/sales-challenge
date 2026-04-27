<?php

namespace Database\Factories;

use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;

/**
 * @extends Factory<SaleProduct>
 */
class SaleProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = SaleProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sale_id' => Sale::factory(),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 50, max: 5000),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}

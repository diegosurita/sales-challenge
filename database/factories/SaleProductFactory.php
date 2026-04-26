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
            'product_id' => Product::factory()->create()->id,
            'sale_id' => Sale::factory()->create()->id,
            'price' => fake()->randomFloat(2, 10, 1000),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(asText: true, nb: 2),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 1, max: 10000),
        ];
    }
}
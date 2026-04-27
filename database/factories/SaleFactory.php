<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-12 months', 'now');

        return [
            'client_id' => Client::factory(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}

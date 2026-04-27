<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $catalog = [
            ['name' => 'Hardware Installation & Setup', 'price' => 150.00],
            ['name' => 'Network Configuration & Setup', 'price' => 299.00],
            ['name' => 'On-site Technical Support (per hour)', 'price' => 120.00],
            ['name' => 'Hardware Repair & Diagnostics', 'price' => 199.00],
            ['name' => 'System Imaging & Deployment', 'price' => 179.00],
            ['name' => 'Data Migration Service', 'price' => 299.00],
            ['name' => 'Firewall Configuration & Tuning', 'price' => 349.00],
            ['name' => 'Server Rack Installation', 'price' => 499.00],
        ];

        $service = fake()->randomElement($catalog);

        return [
            'name' => $service['name'],
            'price' => $service['price'],
            'available' => fake()->boolean(80),
            'product_id' => null,
        ];
    }
}

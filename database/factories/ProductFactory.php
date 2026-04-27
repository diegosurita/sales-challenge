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
        $catalog = [
            ['name' => 'Dell Latitude 5540 Laptop', 'price' => 1299.00],
            ['name' => 'HP EliteBook 840 G10 Laptop', 'price' => 1199.00],
            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'price' => 1499.00],
            ['name' => 'Dell OptiPlex 7010 Desktop', 'price' => 899.00],
            ['name' => 'HP ProDesk 400 G9 Desktop', 'price' => 649.00],
            ['name' => 'Dell PowerEdge T550 Tower Server', 'price' => 3499.00],
            ['name' => 'HPE ProLiant ML350 Gen10 Server', 'price' => 4299.00],
            ['name' => 'Cisco Catalyst 2960-X 24-Port Switch', 'price' => 1299.00],
            ['name' => 'Ubiquiti UniFi 6 Pro Access Point', 'price' => 349.00],
            ['name' => 'Fortinet FortiGate 60F Firewall', 'price' => 899.00],
        ];

        $product = fake()->randomElement($catalog);

        return [
            'name' => $product['name'],
            'price' => $product['price'],
            'stock_count' => fake()->numberBetween(0, 30),
        ];
    }
}
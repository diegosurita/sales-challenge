<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $laptopId = Product::where('name', 'Dell Latitude 5540 Laptop')->value('id');
        $switchId = Product::where('name', 'Cisco Catalyst 2960-X 24-Port Switch')->value('id');
        $firewallId = Product::where('name', 'Fortinet FortiGate 60F Firewall')->value('id');
        $serverId = Product::where('name', 'Dell PowerEdge T550 Tower Server')->value('id');

        $services = [
            ['name' => 'Hardware Installation & Setup', 'price' => 150.00, 'available' => true, 'product_id' => $laptopId],
            ['name' => 'Network Configuration & Setup', 'price' => 299.00, 'available' => true, 'product_id' => $switchId],
            ['name' => 'On-site Technical Support (per hour)', 'price' => 120.00, 'available' => true, 'product_id' => null],
            ['name' => 'Hardware Repair & Diagnostics', 'price' => 199.00, 'available' => true, 'product_id' => null],
            ['name' => 'Warranty Extension (1 Year)', 'price' => 249.00, 'available' => true, 'product_id' => null],
            ['name' => 'System Imaging & Deployment', 'price' => 179.00, 'available' => true, 'product_id' => $laptopId],
            ['name' => 'Data Migration Service', 'price' => 299.00, 'available' => true, 'product_id' => null],
            ['name' => 'Firewall Configuration & Tuning', 'price' => 349.00, 'available' => true, 'product_id' => $firewallId],
            ['name' => 'Network Cabling (per point)', 'price' => 89.00, 'available' => false, 'product_id' => null],
            ['name' => 'Server Rack Installation', 'price' => 499.00, 'available' => true, 'product_id' => $serverId],
        ];

        $now = now();
        Service::insert(
            array_map(
                fn (array $service) => array_merge($service, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
                $services
            )
        );
    }
}

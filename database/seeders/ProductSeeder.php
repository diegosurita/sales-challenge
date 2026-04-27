<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Dell Latitude 5540 Laptop', 'price' => 1299.00, 'stock_count' => 12],
            ['name' => 'HP EliteBook 840 G10 Laptop', 'price' => 1199.00, 'stock_count' => 8],
            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'price' => 1499.00, 'stock_count' => 6],
            ['name' => 'Apple MacBook Pro 14" M3', 'price' => 1999.00, 'stock_count' => 4],
            ['name' => 'Dell OptiPlex 7010 Desktop', 'price' => 899.00, 'stock_count' => 15],
            ['name' => 'HP ProDesk 400 G9 Desktop', 'price' => 649.00, 'stock_count' => 20],
            ['name' => 'Dell PowerEdge T550 Tower Server', 'price' => 3499.00, 'stock_count' => 3],
            ['name' => 'HPE ProLiant ML350 Gen10 Server', 'price' => 4299.00, 'stock_count' => 2],
            ['name' => 'Cisco Catalyst 2960-X 24-Port Switch', 'price' => 1299.00, 'stock_count' => 7],
            ['name' => 'Cisco Catalyst 1000 8-Port Switch', 'price' => 349.00, 'stock_count' => 18],
            ['name' => 'Ubiquiti UniFi 6 Pro Access Point', 'price' => 349.00, 'stock_count' => 14],
            ['name' => 'Fortinet FortiGate 60F Firewall', 'price' => 899.00, 'stock_count' => 9],
            ['name' => 'Dell UltraSharp 27" 4K Monitor', 'price' => 699.00, 'stock_count' => 22],
            ['name' => 'LG 27" IPS Full HD Monitor', 'price' => 199.00, 'stock_count' => 30],
            ['name' => 'HP LaserJet Pro M404dn Printer', 'price' => 299.00, 'stock_count' => 11],
            ['name' => 'Canon PIXMA G3270 All-in-One Printer', 'price' => 249.00, 'stock_count' => 10],
            ['name' => 'Synology DiskStation DS923+ NAS', 'price' => 549.00, 'stock_count' => 5],
            ['name' => 'Western Digital 4TB Red Plus NAS HDD', 'price' => 89.00, 'stock_count' => 28],
            ['name' => 'Samsung 870 EVO 1TB SATA SSD', 'price' => 79.00, 'stock_count' => 25],
            ['name' => 'Kingston 16GB DDR4 3200MHz RAM', 'price' => 49.00, 'stock_count' => 30],
            ['name' => 'Kingston 32GB DDR4 3200MHz RAM', 'price' => 89.00, 'stock_count' => 27],
            ['name' => 'Logitech MX Keys Business Keyboard', 'price' => 109.00, 'stock_count' => 19],
            ['name' => 'Logitech MX Master 3S Mouse', 'price' => 99.00, 'stock_count' => 21],
            ['name' => 'APC Smart-UPS 1500VA UPS', 'price' => 549.00, 'stock_count' => 6],
            ['name' => 'TP-Link 8-Port Gigabit Unmanaged Switch', 'price' => 29.00, 'stock_count' => 0],
        ];

        $now = now();
        Product::insert(
            array_map(
                fn (array $product) => array_merge($product, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
                $products
            )
        );
    }
}
<?php

namespace Database\Seeders;

use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
use Illuminate\Database\Seeder;

class SaleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleProduct::factory(20)->create();
    }
}

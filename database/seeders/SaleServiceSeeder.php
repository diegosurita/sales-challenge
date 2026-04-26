<?php

namespace Database\Seeders;

use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleService;
use Illuminate\Database\Seeder;

class SaleServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleService::factory(10)->create();
    }
}
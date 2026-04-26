<?php

namespace Database\Seeders;

use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sale::factory(10)->create();
    }
}

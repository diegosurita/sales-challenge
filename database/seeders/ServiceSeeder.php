<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;

use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory(15)->create();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $clientIds = Client::pluck('id')->toArray();

        Sale::factory(20)
            ->sequence(fn ($sequence) => [
                'client_id' => fake()->randomElement($clientIds),
                'created_at' => fake()->dateTimeBetween('-12 months', 'now'),
            ])
            ->create();
    }
}

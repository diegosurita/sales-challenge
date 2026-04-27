<?php

namespace Database\Factories;

use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = [
            'Horizon Tech Solutions',
            'BluePeak Consulting',
            'Meridian Healthcare Systems',
            'Apex Infrastructure Ltd.',
            'ClearPath IT Services',
            'Vertex Digital Group',
            'NorthStar Financial Corp.',
            'Synergy Business Solutions',
            'TerraNet Communications',
            'CoreLogic Enterprises',
            'Pinnacle Data Systems',
            'Allied Manufacturing Inc.',
            'Summit Cloud Partners',
            'Nexus Engineering Group',
            'Harbourview Logistics',
            'Ironclad Security Solutions',
            'Brightline Education Corp.',
            'Cascade Retail Group',
            'Orion Media & Publishing',
            'Granite Capital Partners',
            'Silverline Legal Group',
            'Redwood Construction Co.',
            'Pacific Biotech Inc.',
            'Keystone Energy Ltd.',
            'Stormlight Networks',
            'Ember Analytics Corp.',
            'Frostpeak Mining Co.',
            'Metro Healthcare Partners',
            'Crown Real Estate Group',
            'Vector Supply Chain Ltd.',
        ];

        return [
            'name' => fake()->unique()->randomElement($companies),
        ];
    }
}
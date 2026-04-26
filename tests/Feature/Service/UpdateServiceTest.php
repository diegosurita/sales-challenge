<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should update a service and redirect to index', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create(['name' => 'Original Service', 'price' => 100]);

    $updateData = ['name' => 'Updated Service', 'price' => 199.99];

    $response = actingAs($user)->put(route('services.update', $service->id), $updateData);

    $response->assertRedirect(route('services.index'));

    $service->refresh();

    expect($service->name)->toBe('Updated Service');
    expect((float) $service->price)->toBe(199.99);
});

it('should validate required fields on update', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create();

    $response = actingAs($user)->put(route('services.update', $service->id), []);

    $response->assertSessionHasErrors(['name', 'price']);
});

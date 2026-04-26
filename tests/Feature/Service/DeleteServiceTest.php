<?php

use Module\Auth\Infrastructure\Persistence\Eloquent\Models\User;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;

use function Pest\Laravel\actingAs;

it('should delete a service and return no content', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create();

    $response = actingAs($user)->delete(route('services.destroy', $service->id));

    $response->assertNoContent();

    $service->refresh();
    expect($service->trashed())->toBeTrue();
});

it('should return 404 when deleting non-existent service', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->delete(route('services.destroy', 999999));

    $response->assertNotFound();
});

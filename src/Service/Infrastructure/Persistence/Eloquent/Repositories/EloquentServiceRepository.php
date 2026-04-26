<?php

namespace Module\Service\Infrastructure\Persistence\Eloquent\Repositories;

use InvalidArgumentException;
use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Core\DTOs\ServiceFormDTO;
use Module\Service\Core\Entities\ProductEntity;
use Module\Service\Core\Entities\ServiceEntity;
use Module\Service\Infrastructure\Persistence\Eloquent\Models\Service;
use Module\Shared\Core\Exceptions\NotFoundException;

class EloquentServiceRepository implements ServiceRepositoryContract
{
    public function getAll(): array
    {
        return Service::with('product')->get()->map(fn (Service $service) => new ServiceEntity(
            id: $service->id,
            name: $service->name,
            price: (float) $service->price,
            available: $service->available,
            product: $service->product ? new ProductEntity(id: $service->product->id, name: $service->product->name) : null,
        ))->toArray();
    }

    public function createService(ServiceFormDTO $dto): void
    {
        Service::create([
            'name' => $dto->name,
            'price' => $dto->price,
            'available' => $dto->available,
            'product_id' => $dto->productId,
        ]);
    }

    public function getByID(int $id): ServiceEntity
    {
        $service = Service::with('product')->find($id);

        if (! $service) {
            throw new NotFoundException('Service', $id);
        }

        return new ServiceEntity(
            id: $service->id,
            name: $service->name,
            price: (float) $service->price,
            available: $service->available,
            product: $service->product ? new ProductEntity(id: $service->product->id, name: $service->product->name) : null,
        );
    }

    /**
     * @param  int[]  $ids
     * @return array<int, ServiceEntity>
     */
    public function getManyByIDs(array $ids): array
    {
        return Service::with('product')->whereIn('id', $ids)
            ->get()
            ->keyBy('id')
            ->map(fn (Service $service) => new ServiceEntity(
                id: $service->id,
                name: $service->name,
                price: (float) $service->price,
                available: $service->available,
                product: $service->product ? new ProductEntity(id: $service->product->id, name: $service->product->name) : null,
            ))
            ->toArray();
    }

    public function updateService(ServiceFormDTO $dto): void
    {
        if ($dto->id === null) {
            throw new InvalidArgumentException('Service ID is required for update.');
        }

        $service = Service::find($dto->id);

        if (! $service) {
            throw new NotFoundException('Service', $dto->id);
        }

        $service->update([
            'name' => $dto->name,
            'price' => $dto->price,
            'available' => $dto->available,
            'product_id' => $dto->productId,
        ]);
    }

    public function delete(int $id): void
    {
        $service = Service::find($id);

        if (! $service) {
            throw new NotFoundException('Service', $id);
        }

        $service->delete();
    }
}

<?php

namespace Module\Product\Infrastructure\Persistence\Eloquent\Repositories;

use InvalidArgumentException;
use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Shared\Core\Exceptions\NotFoundException;

class EloquentProductRepository implements ProductRepositoryContract
{
    public function getAll(): array
    {
        return Product::all()->map(fn (Product $product) => new ProductEntity(
            id: $product->id,
            name: $product->name,
            price: (float) $product->price,
            stockCount: $product->stock_count !== null ? (int) $product->stock_count : null,
        ))->toArray();
    }

    public function createProduct(ProductFormDTO $dto): void
    {
        Product::create([
            'name' => $dto->name,
            'price' => $dto->price,
        ]);
    }

    public function getByID(int $id): ProductEntity
    {
        $product = Product::find($id);

        if (! $product) {
            throw new NotFoundException('Product', $id);
        }

        return new ProductEntity(
            id: $product->id,
            name: $product->name,
            price: (float) $product->price,
            stockCount: $product->stock_count !== null ? (int) $product->stock_count : null,
        );
    }

    /**
     * @param  int[]  $ids
     * @return array<int, ProductEntity>
     */
    public function getManyByIDs(array $ids): array
    {
        return Product::whereIn('id', $ids)
            ->get()
            ->keyBy('id')
            ->map(fn (Product $product) => new ProductEntity(
                id: $product->id,
                name: $product->name,
                price: (float) $product->price,
                stockCount: $product->stock_count !== null ? (int) $product->stock_count : null,
            ))
            ->toArray();
    }

    public function updateProduct(ProductFormDTO $dto): void
    {
        if ($dto->id === null) {
            throw new InvalidArgumentException('Product ID is required for update.');
        }

        $product = Product::find($dto->id);

        if (! $product) {
            throw new NotFoundException('Product', $dto->id);
        }

        $product->update([
            'name' => $dto->name,
            'price' => $dto->price,
        ]);
    }

    public function delete(int $id): void
    {
        $product = Product::find($id);

        if (! $product) {
            throw new NotFoundException('Product', $id);
        }

        $product->delete();
    }
}

<?php

namespace Module\Product\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\Entities\ProductEntity;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;
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

    public function createProduct(ProductFormDTO $dto): int
    {
        $product = Product::create([
            'name' => $dto->name,
            'price' => $dto->price,
        ]);

        return (int) $product->id;
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

    public function updateStockCount(int $productId, int $stockCount): void
    {
        $product = Product::find($productId);

        if (! $product) {
            throw new NotFoundException('Product', $productId);
        }

        $product->update(['stock_count' => $stockCount]);
    }

    public function delete(int $id): void
    {
        $product = Product::find($id);

        if (! $product) {
            throw new NotFoundException('Product', $id);
        }

        $product->delete();
    }

    /**
     * @return array<int, array{name: string, total_sold: int}>
     */
    public function getTopSellingProducts(int $limit): array
    {
        return SaleProduct::query()
            ->join('products', 'sale_products.product_id', '=', 'products.id')
            ->select([
                'products.name',
                DB::raw('SUM(sale_products.quantity) as total_sold'),
            ])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'total_sold' => (int) $row->total_sold,
            ])
            ->toArray();
    }
}

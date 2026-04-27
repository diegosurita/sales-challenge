<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Product\Infrastructure\Persistence\Eloquent\Models\Product;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\Sale;
use Module\Sale\Infrastructure\Persistence\Eloquent\Models\SaleProduct;

class SaleProductSeeder extends Seeder
{
    public function run(): void
    {
        $sales = Sale::all();
        $productIds = Product::pluck('id')->toArray();

        foreach ($sales as $sale) {
            $selectedProductIds = fake()->randomElements($productIds, rand(2, 4));

            foreach ($selectedProductIds as $productId) {
                $product = Product::find($productId);

                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'price' => $product->price,
                    'quantity' => rand(1, 5),
                ]);
            }
        }
    }
}

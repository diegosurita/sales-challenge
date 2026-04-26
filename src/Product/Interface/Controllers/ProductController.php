<?php

namespace Module\Product\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\UseCases\CreateProductUseCase;
use Module\Product\Core\UseCases\DeleteProductUseCase;
use Module\Product\Core\UseCases\GetProductByIDUseCase;
use Module\Product\Core\UseCases\GetProductsUseCase;
use Module\Product\Core\UseCases\UpdateProductUseCase;
use Module\Shared\Core\Exceptions\NotFoundException;

class ProductController extends Controller
{
    public function index(GetProductsUseCase $useCase)
    {
        $products = $useCase->execute();

        return Inertia::render('Product/ProductsList', [
            'products' => array_map(fn ($product) => $product->toArray(), $products),
            'successMessage' => session('success'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Product/ProductForm');
    }

    public function edit(int $id, GetProductByIDUseCase $useCase)
    {
        try {
            $product = $useCase->execute($id);

            return Inertia::render('Product/ProductForm', [
                'product' => $product->toArray(),
            ]);
        } catch (NotFoundException $e) {
            abort(404);
        }
    }

    public function store(Request $request, CreateProductUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $dto = new ProductFormDTO(
            name: $request->name,
            price: (float) $request->price,
        );

        $useCase->execute($dto);

        session()->flash('success', 'Product created successfully.');

        return redirect()->route('products.index');
    }

    public function update(Request $request, int $id, UpdateProductUseCase $useCase)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $dto = new ProductFormDTO(
            name: $request->name,
            price: (float) $request->price,
            id: $id,
        );

        $useCase->execute($dto);

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route('products.index');
    }

    public function destroy(int $id, DeleteProductUseCase $useCase)
    {
        try {
            $useCase->execute($id);

            return response()->noContent();
        } catch (NotFoundException $e) {
            abort(404);
        }
    }
}
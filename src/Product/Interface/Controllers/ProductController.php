<?php

namespace Module\Product\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Module\Product\Core\DTOs\ProductFormDTO;
use Module\Product\Core\DTOs\RegisterStockDTO;
use Module\Product\Core\Enums\StockLedgerReason;
use Module\Product\Core\UseCases\CreateProductUseCase;
use Module\Product\Core\UseCases\DeleteProductUseCase;
use Module\Product\Core\UseCases\GetProductByIDUseCase;
use Module\Product\Core\UseCases\GetProductsUseCase;
use Module\Product\Core\UseCases\GetProductStockLedgerEntriesUseCase;
use Module\Product\Core\UseCases\RegisterProductStockUseCase;
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

    public function edit(int $id, GetProductByIDUseCase $getProductUseCase, GetProductStockLedgerEntriesUseCase $getLedgerEntriesUseCase)
    {
        try {
            $product = $getProductUseCase->execute($id);
            $ledgerEntries = $getLedgerEntriesUseCase->execute(productId: $id);

            return Inertia::render('Product/ProductForm', [
                'product' => $product->toArray(),
                'stockLedgerEntries' => array_map(fn ($entry) => $entry->toArray(), $ledgerEntries),
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
            'stock_count' => 'nullable|integer|min:0',
        ]);

        $dto = new ProductFormDTO(
            name: $request->name,
            price: (float) $request->price,
            stockCount: $request->stock_count !== null ? (int) $request->stock_count : null,
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

    public function storeStockLedger(Request $request, int $id, RegisterProductStockUseCase $registerStockUseCase)
    {
        try {
            $request->validate([
                'reason' => ['required', 'string', 'in:' . implode(',', array_column(StockLedgerReason::cases(), 'value'))],
                'quantity' => 'required|integer|not_in:0',
            ]);

            $dto = new RegisterStockDTO(
                productId: $id,
                reason: StockLedgerReason::from($request->reason),
                quantity: (int) $request->quantity,
            );

            $registerStockUseCase->execute(dto: $dto);

            session()->flash('success', 'Stock registered successfully.');

            return redirect()->route('products.edit', ['product' => $id]);
        } catch (NotFoundException $e) {
            abort(404);
        }
    }
}
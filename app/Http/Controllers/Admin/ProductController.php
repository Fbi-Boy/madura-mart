<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\StockMovementService;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(
        ProductStoreRequest $request
    ): JsonResponse|RedirectResponse {
        $product = DB::transaction(function () use ($request): Product {
            $data = $request->validated();

            $initialStock = (int) ($data['stock'] ?? 0);

            // Stok awal dicatat melalui StockMovementService.
            $data['stock'] = 0;

            $product = Product::create($data + [
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($initialStock > 0) {
                StockMovementService::apply(
                    $product,
                    $initialStock,
                    'initial',
                    $request->user(),
                    'product',
                    $product->id,
                    'Stok awal produk',
                );
            }

            return $product;
        });

        // Broadcast setelah transaksi database berhasil.
        try {
            broadcast(new ProductCreated(
                productId: $product->id,
                productName: $product->name,
                createdBy: $request->user()->name,
                createdById: $request->user()->id,
            ))->toOthers();
        } catch (BroadcastException $exception) {
            // Produk sudah tersimpan. Gangguan Reverb tidak boleh
            // membuat pengguna mengira proses penyimpanan gagal.
            Log::warning('Broadcast produk baru gagal dikirim.', [
                'product_id' => $product->id,
                'error' => $exception->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                ],
            ], 201);
        }

        return to_route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(
        ProductUpdateRequest $request,
        Product $product
    ): RedirectResponse {
        DB::transaction(function () use ($request, $product): void {
            $data = $request->validated();

            $targetStock = (int) ($data['stock'] ?? $product->stock);

            unset($data['stock']);

            $product->update($data + [
                'is_active' => $request->boolean('is_active'),
            ]);

            $difference = $targetStock - (int) $product->stock;

            if ($difference !== 0) {
                StockMovementService::apply(
                    $product,
                    $difference,
                    'adjustment',
                    $request->user(),
                    'product',
                    $product->id,
                    'Penyesuaian stok melalui master produk',
                );
            }
        });

        return to_route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

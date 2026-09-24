<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
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
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        Product::create($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated() + [
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

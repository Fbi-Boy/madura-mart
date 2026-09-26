<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $sort = (string) $request->query('sort', 'newest');
        $allowedSorts = ['newest', 'price_asc', 'price_desc', 'name_asc'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'newest';

        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->whereHas('category', fn ($query) => $query
                    ->where('slug', $category)
                    ->where('is_active', true));
            })
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price')->orderBy('name'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price')->orderBy('name'))
            ->when($sort === 'name_asc', fn ($query) => $query->orderBy('name')->orderByDesc('updated_at'))
            ->when($sort === 'newest', fn ($query) => $query->latest('updated_at'))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->whereHas('products', fn ($query) => $query
                ->where('is_active', true)
                ->where('stock', '>', 0))
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('customer.catalog.index', compact('products', 'categories', 'search', 'category', 'sort'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with('category:id,name,slug')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->firstOrFail();

        return view('customer.catalog.show', compact('product'));
    }
}

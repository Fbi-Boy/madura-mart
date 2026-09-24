<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('sku', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();
        $outOfStock = Product::where('stock', 0)->count();
        $lowStock = Product::whereBetween('stock', [1, 10])->where('is_active', true)->count();

        return view('admin.monitoring.produk.index', compact(
            'products',
            'search',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'outOfStock',
            'lowStock'
        ));
    }
}

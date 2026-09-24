<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StokController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $products = Product::with('category')
            ->when($search !== '', fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock');
        $lowStock = Product::where('stock', '<=', 5)->count();
        $outOfStock = Product::where('stock', 0)->count();

        return view('admin.report.stok.index', compact(
            'products',
            'search',
            'totalProducts',
            'totalStock',
            'lowStock',
            'outOfStock'
        ));
    }
}
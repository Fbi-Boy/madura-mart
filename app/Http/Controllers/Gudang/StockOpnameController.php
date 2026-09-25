<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockOpname;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'sku', 'name', 'stock', 'unit']);

        $recentOpnames = StockOpname::query()
            ->with('user:id,name')
            ->withCount('items')
            ->latest()
            ->limit(8)
            ->get();

        return view('gudang.stock-opname.index', compact('products', 'recentOpnames'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
            'actual_stock' => ['required', 'array', 'min:1'],
            'actual_stock.*' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);

        DB::transaction(function () use ($validated): void {
            $products = Product::query()
                ->where('is_active', true)
                ->whereIn('id', array_keys($validated['actual_stock']))
                ->lockForUpdate()
                ->get(['id', 'stock']);

            $opname = StockOpname::query()->create([
                'user_id' => auth()->id(),
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'completed_at' => now(),
            ]);

            foreach ($products as $product) {
                $actualStock = (int) $validated['actual_stock'][$product->id];
                $systemStock = (int) $product->stock;

                $opname->items()->create([
                    'product_id' => $product->id,
                    'system_stock' => $systemStock,
                    'actual_stock' => $actualStock,
                    'difference' => $actualStock - $systemStock,
                ]);

                if ($actualStock !== $systemStock) {
                    $product->update(['stock' => $actualStock]);
                    StockMovementService::record($product, $actualStock - $systemStock, 'adjustment', auth()->user(), 'stock_opname', $opname->id, 'Penyesuaian hasil stock opname');
                }
            }
        });

        return to_route('gudang.stock-opname.index')
            ->with('success', 'Stock opname berhasil disimpan dan stok disesuaikan.');
    }
}

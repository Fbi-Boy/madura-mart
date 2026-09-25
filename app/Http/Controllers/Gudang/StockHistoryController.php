<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMovement::query()
            ->with(['product:id,name,sku,unit', 'user:id,name'])
            ->latest('occurred_at');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('date')) {
            $query->whereDate('occurred_at', $request->string('date')->toString());
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->whereHas('product', fn ($product) => $product
                ->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"));
        }

        $movements = $query->paginate(15)->withQueryString();

        $summaryQuery = StockMovement::query();

        if ($request->filled('date')) {
            $summaryQuery->whereDate('occurred_at', $request->string('date')->toString());
        }

        $summary = [
            'inbound' => (int) (clone $summaryQuery)->where('quantity', '>', 0)->sum('quantity'),
            'outbound' => abs((int) (clone $summaryQuery)->where('quantity', '<', 0)->sum('quantity')),
            'adjustment' => (int) (clone $summaryQuery)->where('type', 'adjustment')->sum('quantity'),
        ];

        return view('gudang.riwayat-stok.index', compact('movements', 'summary'));
    }
}

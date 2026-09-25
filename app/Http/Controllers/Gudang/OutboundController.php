<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OutboundController extends Controller
{
    public function index(Request $request): View
    {
        $query = SaleItem::query()
            ->with([
                'product:id,name,sku,unit',
                'sale:id,invoice,sale_date,status',
            ])
            ->whereHas('sale', fn ($sale) => $sale->where('status', 'paid'));

        if ($request->filled('date')) {
            $query->whereHas('sale', fn ($sale) => $sale->whereDate('sale_date', $request->date));
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function ($item) use ($search) {
                $item->whereHas('product', fn ($product) => $product
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%"))
                    ->orWhereHas('sale', fn ($sale) => $sale
                        ->where('invoice', 'like', "%{$search}%"));
            });
        }

        $outboundItems = $query
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        $today = now()->toDateString();

        $todayUnits = SaleItem::query()
            ->whereHas('sale', fn ($sale) => $sale
                ->where('status', 'paid')
                ->whereDate('sale_date', $today))
            ->sum('quantity');

        $todayTransactions = SaleItem::query()
            ->whereHas('sale', fn ($sale) => $sale
                ->where('status', 'paid')
                ->whereDate('sale_date', $today))
            ->distinct('sale_id')
            ->count('sale_id');

        return view('gudang.barang-keluar.index', compact(
            'outboundItems',
            'todayUnits',
            'todayTransactions',
        ));
    }
}

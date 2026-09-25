<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $purchases = Purchase::query()
            ->with(['supplier:id,name', 'user:id,name'])
            ->whereBetween('purchase_date', [$from->startOfDay(), $to->endOfDay()])
            ->latest('purchase_date')
            ->paginate(15)
            ->withQueryString();

        $baseQuery = Purchase::query()
            ->whereBetween('purchase_date', [$from->startOfDay(), $to->endOfDay()]);

        $totalTransactions = (clone $baseQuery)->count();
        $totalPurchases = (float) (clone $baseQuery)->sum('total');
        $receivedTransactions = (clone $baseQuery)->where('status', 'received')->count();
        $draftTransactions = (clone $baseQuery)->where('status', 'draft')->count();

        return view('admin.report.pembelian.index', compact(
            'purchases',
            'from',
            'to',
            'totalTransactions',
            'totalPurchases',
            'receivedTransactions',
            'draftTransactions',
        ));
    }
}

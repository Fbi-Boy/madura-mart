<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $sales = Sale::with(['customer', 'user', 'shift'])
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$from->startOfDay(), $to->endOfDay()])
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        $totalTransactions = $sales->total();
        $totalSales = (float) Sale::where('status', 'paid')
            ->whereBetween('sale_date', [$from->startOfDay(), $to->endOfDay()])
            ->sum('total');

        return view('admin.report.penjualan.index', compact('sales', 'from', 'to', 'totalTransactions', 'totalSales'));
    }
}
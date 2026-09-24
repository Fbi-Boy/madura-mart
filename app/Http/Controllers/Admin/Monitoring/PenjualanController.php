<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $sales = Sale::with(['customer', 'user', 'shift'])
            ->when($search !== '', fn ($query) => $query
                ->where('invoice', 'like', "%{$search}%"))
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        $todaySales = (float) Sale::where('status', 'paid')
            ->whereDate('sale_date', today())
            ->sum('total');

        $todayTransactions = Sale::where('status', 'paid')
            ->whereDate('sale_date', today())
            ->count();

        return view('admin.monitoring.penjualan.index', compact(
            'sales',
            'search',
            'todaySales',
            'todayTransactions'
        ));
    }
}
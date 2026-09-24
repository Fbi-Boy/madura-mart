<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $purchases = Purchase::with(['supplier', 'user'])
            ->withCount('items')
            ->when($search !== '', fn ($query) => $query->where('invoice', 'like', "%{$search}%"))
            ->latest('purchase_date')
            ->paginate(15)
            ->withQueryString();

        $todayPurchases = (float) Purchase::whereDate('purchase_date', today())->sum('total');
        $todayTransactions = Purchase::whereDate('purchase_date', today())->count();
        $pending = Purchase::whereIn('status', ['draft'])->count();

        return view('admin.monitoring.pembelian.index', compact(
            'purchases',
            'search',
            'todayPurchases',
            'todayTransactions',
            'pending'
        ));
    }
}
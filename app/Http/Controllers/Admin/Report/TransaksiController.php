<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();
        $status = $request->string('status')->toString();

        $transactions = Sale::query()
            ->with(['customer:id,name', 'user:id,name', 'shift:id,user_id'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->whereBetween('sale_date', [$from->startOfDay(), $to->endOfDay()])
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        $baseQuery = Sale::query()
            ->whereBetween('sale_date', [$from->startOfDay(), $to->endOfDay()]);

        $summary = [
            'all' => (clone $baseQuery)->count(),
            'paid' => (clone $baseQuery)->where('status', 'paid')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'gross' => (float) (clone $baseQuery)->where('status', 'paid')->sum('total'),
        ];

        return view('admin.report.transaksi.index', compact(
            'transactions',
            'from',
            'to',
            'status',
            'summary',
        ));
    }
}

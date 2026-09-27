<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $customers = Customer::query()
            ->select('customers.*')
            ->selectSub(
                Sale::query()->selectRaw('COUNT(*)')
                    ->whereColumn('sales.customer_id', 'customers.id')
                    ->where('sales.status', 'paid')
                    ->whereBetween('sales.sale_date', [$from->startOfDay(), $to->endOfDay()]),
                'transaction_count'
            )
            ->selectSub(
                Sale::query()->selectRaw('COALESCE(SUM(total), 0)')
                    ->whereColumn('sales.customer_id', 'customers.id')
                    ->where('sales.status', 'paid')
                    ->whereBetween('sales.sale_date', [$from->startOfDay(), $to->endOfDay()]),
                'total_spent'
            )
            ->orderByDesc('total_spent')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalCustomers = $customers->total();
        $periodNewCustomers = Customer::query()
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->count();

        $periodRevenue = (float) Sale::query()
            ->whereNotNull('customer_id')
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$from->startOfDay(), $to->endOfDay()])
            ->sum('total');

        return view('admin.report.pelanggan.index', compact(
            'customers',
            'from',
            'to',
            'totalCustomers',
            'periodNewCustomers',
            'periodRevenue',
        ));
    }
}

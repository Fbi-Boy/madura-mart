<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengirimanController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $baseQuery = Order::query()
            ->whereBetween('order_date', [$from->startOfDay(), $to->endOfDay()]);

        $orders = (clone $baseQuery)
            ->with(['customer:id,name', 'courier:id,name'])
            ->latest('order_date')
            ->paginate(15)
            ->withQueryString();

        $statusSummary = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'shipped' => (clone $baseQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $baseQuery)->where('status', 'delivered')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $totalShipments = array_sum($statusSummary);
        $assignedShipments = (clone $baseQuery)->whereNotNull('courier_id')->count();
        $shippingValue = (float) (clone $baseQuery)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');

        return view('admin.report.pengiriman.index', compact(
            'orders',
            'from',
            'to',
            'statusSummary',
            'totalShipments',
            'assignedShipments',
            'shippingValue',
        ));
    }
}

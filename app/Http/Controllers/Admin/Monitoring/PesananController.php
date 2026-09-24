<?php

namespace App\Http\Controllers\Admin\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $orders = Order::with(['customer', 'courier'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$search}%"));
            })
            ->latest('order_date')
            ->paginate(15)
            ->withQueryString();

        $totalOrders = Order::count();
        $pending = Order::where('status', 'pending')->count();
        $processing = Order::where('status', 'processing')->count();
        $shipping = Order::where('status', 'shipped')->count();
        $delivered = Order::where('status', 'delivered')->count();
        $cancelled = Order::where('status', 'cancelled')->count();
        $todayTotal = (float) Order::whereDate('order_date', today())
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        return view('admin.monitoring.pesanan.index', compact(
            'orders',
            'search',
            'totalOrders',
            'pending',
            'processing',
            'shipping',
            'delivered',
            'cancelled',
            'todayTotal'
        ));
    }
}

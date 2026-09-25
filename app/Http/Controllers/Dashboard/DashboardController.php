<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $role = auth()->user()->role;

        if ($role !== 'admin') {
            return match ($role) {
                'kasir' => view('kasir.dashboard'),
                'kurir' => view('kurir.dashboard'),
                'customer' => view('customer.dashboard'),
                'purchasing' => view('purchasing.dashboard'),
                'super-admin' => view('super-admin.dashboard'),
                default => view('dashboard.index'),
            };
        }

        return $this->adminDashboard();
    }

    private function adminDashboard(): View
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $monthlyRevenue = Sale::query()
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$monthStart, Carbon::now()])
            ->sum('total');

        $todayTransactions = Sale::query()
            ->where('status', 'paid')
            ->whereDate('sale_date', $today)
            ->count();

        $activeProducts = Product::query()
            ->where('is_active', true)
            ->count();

        $lowStockProducts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->count();

        $pendingOrders = Order::query()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $pendingPurchases = Purchase::query()
            ->whereIn('status', ['pending', 'ordered'])
            ->count();

        $activeCustomers = Customer::query()
            ->where('is_active', true)
            ->count();

        $activeCouriers = Courier::query()
            ->where('is_active', true)
            ->count();

        $recentSales = Sale::query()
            ->with(['customer:id,name'])
            ->where('status', 'paid')
            ->latest('sale_date')
            ->limit(6)
            ->get(['id', 'invoice', 'customer_id', 'sale_date', 'total', 'payment_method']);

        $recentOrders = Order::query()
            ->with(['customer:id,name', 'courier:id,name'])
            ->latest('order_date')
            ->limit(5)
            ->get(['id', 'order_number', 'customer_id', 'courier_id', 'order_date', 'total', 'status']);

        $topProducts = SaleItem::query()
            ->with('product:id,name')
            ->whereHas('sale', function ($query) use ($monthStart) {
                $query->where('status', 'paid')
                    ->whereBetween('sale_date', [$monthStart, Carbon::now()]);
            })
            ->selectRaw('product_id, SUM(quantity) as quantity_sold, SUM(subtotal) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get();

        $stockAlerts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->limit(6)
            ->get(['id', 'name', 'sku', 'stock', 'unit']);

        return view('admin.dashboard', compact(
            'monthlyRevenue',
            'todayTransactions',
            'activeProducts',
            'lowStockProducts',
            'pendingOrders',
            'pendingPurchases',
            'activeCustomers',
            'activeCouriers',
            'recentSales',
            'recentOrders',
            'topProducts',
            'stockAlerts',
        ));
    }
}

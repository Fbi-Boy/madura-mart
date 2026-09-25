<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseItem;
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
                'kasir' => $this->cashierDashboard(),
                'gudang' => $this->warehouseDashboard(),
                'kurir' => view('kurir.dashboard'),
                'customer' => view('customer.dashboard'),
                'purchasing' => $this->purchasingDashboard(),
                'super-admin' => view('super-admin.dashboard'),
                default => view('dashboard.index'),
            };
        }

        return $this->adminDashboard();
    }



    private function purchasingDashboard(): View
    {
        $today = Carbon::today();

        $activeSuppliers = AppModelsSupplier::query()
            ->where('is_active', true)
            ->count();

        $todayPurchases = (float) Purchase::query()
            ->whereDate('purchase_date', $today)
            ->sum('total');

        $todayTransactions = Purchase::query()
            ->whereDate('purchase_date', $today)
            ->count();

        $draftPurchases = Purchase::query()
            ->where('status', 'draft')
            ->count();

        $receivedToday = Purchase::query()
            ->where('status', 'received')
            ->whereDate('purchase_date', $today)
            ->count();

        $statusSummary = [
            'draft' => Purchase::query()->where('status', 'draft')->count(),
            'received' => Purchase::query()->where('status', 'received')->count(),
            'cancelled' => Purchase::query()->where('status', 'cancelled')->count(),
        ];

        $recentPurchases = Purchase::query()
            ->with('supplier:id,name')
            ->withCount('items')
            ->latest('purchase_date')
            ->limit(7)
            ->get([
                'id',
                'invoice',
                'supplier_id',
                'purchase_date',
                'total',
                'status',
            ]);

        $supplierPurchases = Purchase::query()
            ->with('supplier:id,name')
            ->selectRaw('supplier_id, COUNT(*) as transaction_count, SUM(total) as total_value')
            ->groupBy('supplier_id')
            ->orderByDesc('total_value')
            ->limit(5)
            ->get();

        return view('purchasing.dashboard', compact(
            'activeSuppliers',
            'todayPurchases',
            'todayTransactions',
            'draftPurchases',
            'receivedToday',
            'statusSummary',
            'recentPurchases',
            'supplierPurchases',
        ));
    }

    private function cashierDashboard(): View
    {
        $today = Carbon::today();
        $userId = auth()->id();

        $openShift = CashierShift::query()
            ->where('user_id', $userId)
            ->where('status', 'open')
            ->first();

        $todaySales = Sale::query()
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->whereDate('sale_date', $today);

        $todayRevenue = (clone $todaySales)->sum('total');
        $todayTransactions = (clone $todaySales)->count();

        $paymentSummary = [
            'cash' => (clone $todaySales)->where('payment_method', 'cash')->sum('total'),
            'transfer' => (clone $todaySales)->where('payment_method', 'transfer')->sum('total'),
            'qris' => (clone $todaySales)->where('payment_method', 'qris')->sum('total'),
        ];

        $shiftCashSales = $openShift
            ? $openShift->sales()->where('status', 'paid')->where('payment_method', 'cash')->sum('total')
            : 0;

        $expectedCash = $openShift
            ? (float) $openShift->opening_cash + (float) $shiftCashSales
            : 0;

        $recentSales = Sale::query()
            ->with('customer:id,name')
            ->where('user_id', $userId)
            ->latest('sale_date')
            ->limit(6)
            ->get(['id', 'invoice', 'customer_id', 'sale_date', 'total', 'payment_method', 'status']);

        $lowStockProducts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(5)
            ->get(['id', 'name', 'sku', 'stock', 'unit']);

        return view('kasir.dashboard', compact(
            'openShift',
            'todayRevenue',
            'todayTransactions',
            'paymentSummary',
            'shiftCashSales',
            'expectedCash',
            'recentSales',
            'lowStockProducts',
        ));
    }

    private function warehouseDashboard(): View
    {
        $today = Carbon::today();

        $activeProducts = Product::query()
            ->where('is_active', true)
            ->count();

        $totalStock = Product::query()
            ->where('is_active', true)
            ->sum('stock');

        $lowStockCount = Product::query()
            ->where('is_active', true)
            ->whereBetween('stock', [1, 10])
            ->count();

        $outOfStockCount = Product::query()
            ->where('is_active', true)
            ->where('stock', 0)
            ->count();

        $inboundToday = PurchaseItem::query()
            ->whereHas('purchase', function ($query) use ($today) {
                $query->where('status', 'received')
                    ->whereDate('purchase_date', $today);
            })
            ->sum('quantity');

        $outboundToday = SaleItem::query()
            ->whereHas('sale', function ($query) use ($today) {
                $query->where('status', 'paid')
                    ->whereDate('sale_date', $today);
            })
            ->sum('quantity');

        $restockProducts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'sku', 'stock', 'unit']);

        $recentInbound = PurchaseItem::query()
            ->with(['product:id,name,sku,unit', 'purchase:id,invoice,supplier_id,purchase_date'])
            ->whereHas('purchase', fn ($query) => $query->where('status', 'received'))
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'purchase_id', 'product_id', 'quantity']);

        $recentOutbound = SaleItem::query()
            ->with(['product:id,name,sku,unit', 'sale:id,invoice,sale_date,status'])
            ->whereHas('sale', fn ($query) => $query->where('status', 'paid'))
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'sale_id', 'product_id', 'quantity']);

        return view('gudang.dashboard', compact(
            'activeProducts',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'inboundToday',
            'outboundToday',
            'restockProducts',
            'recentInbound',
            'recentOutbound',
        ));
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
            ->where('status', 'draft')
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

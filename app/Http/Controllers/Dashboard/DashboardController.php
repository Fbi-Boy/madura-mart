<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CashierShift;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
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
                'kurir' => $this->courierDashboard(),
                'customer' => $this->customerDashboard(),
                'purchasing' => $this->purchasingDashboard(),
                'super-admin' => $this->superAdminDashboard(),
                default => view('dashboard.index'),
            };
        }

        return $this->adminDashboard();
    }



    private function superAdminDashboard(): View
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();

        $totalUsers = User::query()->count();
        $activeProducts = Product::query()->where('is_active', true)->count();
        $activeCustomers = Customer::query()->where('is_active', true)->count();
        $activeCouriers = Courier::query()->where('is_active', true)->count();

        $monthlyRevenue = (float) Sale::query()
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$monthStart, $now])
            ->sum('total');

        $monthlyPurchases = (float) Purchase::query()
            ->where('status', 'received')
            ->whereDate('purchase_date', '>=', $monthStart->toDateString())
            ->whereDate('purchase_date', '<=', $now->toDateString())
            ->sum('total');

        $pendingOrders = Order::query()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $lowStockProducts = Product::query()
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->count();

        $roleSummary = [
            'admin' => User::query()->where('role', 'admin')->count(),
            'super-admin' => User::query()->where('role', 'super-admin')->count(),
            'gudang' => User::query()->where('role', 'gudang')->count(),
            'kasir' => User::query()->where('role', 'kasir')->count(),
            'purchasing' => User::query()->where('role', 'purchasing')->count(),
            'kurir' => User::query()->where('role', 'kurir')->count(),
            'customer' => User::query()->where('role', 'customer')->count(),
        ];

        $recentUsers = User::query()
            ->latest()
            ->limit(6)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        $recentActivities = ActivityLog::query()
            ->with('user:id,name,role')
            ->latest()
            ->limit(8)
            ->get(['id', 'user_id', 'action', 'description', 'created_at']);

        $recentOrders = Order::query()
            ->with('customer:id,name')
            ->latest('order_date')
            ->limit(6)
            ->get(['id', 'order_number', 'customer_id', 'order_date', 'total', 'status']);

        return view('super-admin.dashboard', compact(
            'totalUsers',
            'activeProducts',
            'activeCustomers',
            'activeCouriers',
            'monthlyRevenue',
            'monthlyPurchases',
            'pendingOrders',
            'lowStockProducts',
            'roleSummary',
            'recentUsers',
            'recentOrders',
            'recentActivities',
        ));
    }

    private function purchasingDashboard(): View
    {
        $today = Carbon::today();

        $activeSuppliers = Supplier::query()
            ->where('is_active', true)
            ->count();

        $todayPurchases = (float) Purchase::query()
            ->whereDate('purchase_date', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $todayTransactions = Purchase::query()
            ->whereDate('purchase_date', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $draftPurchases = Purchase::query()
            ->where('status', 'draft')
            ->count();

        $receivedToday = Purchase::query()
            ->where('status', 'received')
            ->whereDate('purchase_date', $today)
            ->count();

        $receivedValueToday = (float) Purchase::query()
            ->where('status', 'received')
            ->whereDate('purchase_date', $today)
            ->sum('total');

        $draftPurchaseValue = (float) Purchase::query()
            ->where('status', 'draft')
            ->sum('total');

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
            ->where('status', '!=', 'cancelled')
            ->with('supplier:id,name')
            ->selectRaw('supplier_id, COUNT(*) as transaction_count, SUM(total) as total_value')
            ->groupBy('supplier_id')
            ->orderByDesc('total_value')
            ->limit(5)
            ->get();

        $procurementTrend = collect(range(5, 0))->map(function (int $monthsAgo) {
            $month = Carbon::today()->startOfMonth()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M'),
                'value' => (float) Purchase::query()
                    ->where('status', 'received')
                    ->whereBetween('purchase_date', [
                        $month->toDateString(),
                        $month->copy()->endOfMonth()->toDateString(),
                    ])
                    ->sum('total'),
            ];
        });

        return view('purchasing.dashboard', compact(
            'activeSuppliers',
            'todayPurchases',
            'todayTransactions',
            'draftPurchases',
            'receivedToday',
            'receivedValueToday',
            'draftPurchaseValue',
            'statusSummary',
            'recentPurchases',
            'supplierPurchases',
            'procurementTrend',
        ));
    }

    private function customerDashboard(): View
    {
        $user = auth()->user();
        $cart = $user->role === 'customer' ? request()->session()->get('customer_cart', []) : [];
        $cartItemCount = collect($cart)->sum(fn ($quantity) => max((int) $quantity, 0));

        $customer = Customer::query()
            ->where('email', $user->email)
            ->first();

        $baseOrders = $customer
            ? Order::query()->where('customer_id', $customer->id)
            : Order::query()->whereRaw('1 = 0');

        $totalOrders = (clone $baseOrders)->count();
        $activeOrders = (clone $baseOrders)
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();
        $completedOrders = (clone $baseOrders)
            ->where('status', 'delivered')
            ->count();
        $cancelledOrders = (clone $baseOrders)
            ->where('status', 'cancelled')
            ->count();

        $totalSpent = (float) (clone $baseOrders)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $statusSummary = [
            'pending' => (clone $baseOrders)->where('status', 'pending')->count(),
            'processing' => (clone $baseOrders)->where('status', 'processing')->count(),
            'shipped' => (clone $baseOrders)->where('status', 'shipped')->count(),
            'delivered' => $completedOrders,
            'cancelled' => $cancelledOrders,
        ];

        $recentOrders = (clone $baseOrders)
            ->with('courier:id,name')
            ->latest('order_date')
            ->limit(6)
            ->get([
                'id',
                'order_number',
                'courier_id',
                'order_date',
                'total',
                'status',
                'delivery_address',
            ]);

        return view('customer.dashboard', compact(
            'customer',
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'cancelledOrders',
            'totalSpent',
            'statusSummary',
            'recentOrders',
            'cartItemCount',
        ));
    }

    private function courierDashboard(): View
    {
        $user = auth()->user();
        $today = Carbon::today();

        $courier = Courier::query()
            ->where('is_active', true)
            ->where('email', $user->email)
            ->first();

        $baseOrders = $courier
            ? Order::query()->where('courier_id', $courier->id)
            : Order::query()->whereRaw('1 = 0');

        $todayOrders = (clone $baseOrders)
            ->whereDate('order_date', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $pendingOrders = (clone $baseOrders)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $shippingOrders = (clone $baseOrders)
            ->where('status', 'shipped')
            ->count();

        // The order schema does not expose a delivered_at timestamp, so this metric
        // intentionally reports total completed assignments instead of guessing a
        // delivery date from order_date.
        $deliveredOrders = (clone $baseOrders)
            ->where('status', 'delivered')
            ->count();

        $activeDeliveryOrders = (clone $baseOrders)
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        $deliveryBase = (clone $baseOrders)
            ->whereIn('status', ['pending', 'processing', 'shipped', 'delivered'])
            ->count();

        $deliveryRate = $deliveryBase > 0
            ? round($deliveredOrders / $deliveryBase * 100, 1)
            : 0;

        $priorityOrders = (clone $baseOrders)
            ->with('customer:id,name')
            ->where('status', 'shipped')
            ->latest('order_date')
            ->limit(4)
            ->get([
                'id',
                'order_number',
                'customer_id',
                'order_date',
                'delivery_address',
                'status',
            ]);

        $statusSummary = [
            'pending' => (clone $baseOrders)->where('status', 'pending')->count(),
            'processing' => (clone $baseOrders)->where('status', 'processing')->count(),
            'shipped' => (clone $baseOrders)->where('status', 'shipped')->count(),
            'delivered' => (clone $baseOrders)->where('status', 'delivered')->count(),
        ];

        $recentOrders = (clone $baseOrders)
            ->with('customer:id,name')
            ->latest('order_date')
            ->limit(7)
            ->get([
                'id',
                'order_number',
                'customer_id',
                'order_date',
                'total',
                'status',
                'delivery_address',
            ]);

        $courierOrderIds = (clone $baseOrders)->select('id');

        $recentDeliveryUpdates = ActivityLog::query()
            ->with('user:id,name')
            ->where('action', 'delivery.status_updated')
            ->where('subject_type', Order::class)
            ->whereIn('subject_id', $courierOrderIds)
            ->latest()
            ->limit(6)
            ->get(['id', 'user_id', 'subject_id', 'description', 'metadata', 'created_at']);

        return view('kurir.dashboard', compact(
            'courier',
            'todayOrders',
            'pendingOrders',
            'shippingOrders',
            'deliveredOrders',
            'activeDeliveryOrders',
            'deliveryRate',
            'priorityOrders',
            'statusSummary',
            'recentOrders',
            'recentDeliveryUpdates',
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

        $pendingPurchaseValue = Purchase::query()
            ->where('status', 'draft')
            ->sum('total');

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
            'pendingPurchaseValue',
            'activeCustomers',
            'activeCouriers',
            'recentSales',
            'recentOrders',
            'topProducts',
            'stockAlerts',
        ));
    }
}

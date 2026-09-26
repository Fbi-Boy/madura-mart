<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function assertDashboardForRole(string $role, string $view): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs($view);
    }

    public function test_kurir_dashboard_uses_courier_assigned_orders(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'kurir@maduramart.test',
            'name' => 'Kurir Madura',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'kurir@maduramart.test',
            'name' => 'Kurir Madura',
            'is_active' => true,
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'delivered',
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'cancelled',
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'courier_id' => null,
            'status' => 'pending',
            'order_date' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('kurir.dashboard')
            ->assertViewHas('todayOrders', 3)
            ->assertViewHas('pendingOrders', 0)
            ->assertViewHas('shippingOrders', 1)
            ->assertViewHas('activeDeliveryOrders', 1)
            ->assertViewHas('deliveryRate', 50.0)
            ->assertViewHas('priorityOrders', fn ($orders) => $orders->count() === 1)
            ->assertViewHas('deliveredToday', 1)
            ->assertViewHas('statusSummary', [
                'pending' => 0,
                'processing' => 0,
                'shipped' => 1,
                'delivered' => 1,
            ]);
    }

    public function test_courier_dashboard_shows_only_its_delivery_activity(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'activity@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'activity@maduramart.test',
            'is_active' => true,
        ]);

        $assignedOrder = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
        ]);

        $otherOrder = Order::factory()->create([
            'status' => 'shipped',
        ]);

        ActivityLog::factory()->create([
            'user_id' => $user->id,
            'action' => 'delivery.status_updated',
            'subject_type' => Order::class,
            'subject_id' => $assignedOrder->id,
            'description' => 'Status pengiriman assigned berubah.',
        ]);

        ActivityLog::factory()->create([
            'user_id' => $user->id,
            'action' => 'delivery.status_updated',
            'subject_type' => Order::class,
            'subject_id' => $otherOrder->id,
            'description' => 'Aktivitas order lain.',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('kurir.dashboard')
            ->assertViewHas('recentDeliveryUpdates', fn ($activities) =>
                $activities->count() === 1
                && $activities->first()->subject_id === $assignedOrder->id
            )
            ->assertSee('Status pengiriman assigned berubah.')
            ->assertDontSee('Aktivitas order lain.');
    }

    public function test_purchasing_users_see_the_purchasing_dashboard(): void
    {
        $this->assertDashboardForRole('purchasing', 'purchasing.dashboard');
    }

    public function test_admin_users_see_the_admin_dashboard(): void
    {
        $this->assertDashboardForRole('admin', 'admin.dashboard');
    }


    public function test_super_admin_dashboard_exposes_recent_activity(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);
        $actor = User::factory()->create(['role' => 'admin', 'name' => 'Admin Activity']);

        ActivityLog::query()->create([
            'user_id' => $actor->id,
            'action' => 'product.created',
            'description' => 'Produk baru berhasil dibuat.',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('super-admin.dashboard')
            ->assertViewHas('recentActivities', fn ($activities) =>
                $activities->count() === 1
                && $activities->first()->action === 'product.created'
                && $activities->first()->user?->name === 'Admin Activity'
            )
            ->assertSee('Produk baru berhasil dibuat.')
            ->assertSee('Admin Activity');
    }

    public function test_super_admin_dashboard_uses_system_and_business_metrics(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        User::factory()->count(2)->create(['role' => 'kasir']);
        Product::factory()->create(['is_active' => true, 'stock' => 3]);
        Product::factory()->create(['is_active' => false, 'stock' => 0]);
        Customer::factory()->create(['is_active' => true]);
        Courier::factory()->create(['is_active' => true]);

        Sale::factory()->create([
            'status' => 'paid',
            'total' => 300000,
            'sale_date' => now(),
        ]);

        Sale::factory()->create([
            'status' => 'cancelled',
            'total' => 900000,
            'sale_date' => now(),
        ]);

        Purchase::factory()->create([
            'status' => 'received',
            'total' => 125000,
            'purchase_date' => now(),
        ]);

        Order::factory()->create(['status' => 'processing']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('super-admin.dashboard')
            ->assertViewHas('totalUsers', fn ($count) => $count >= 3)
            ->assertViewHas('activeProducts', 1)
            ->assertViewHas('activeCustomers', fn ($count) => $count >= 1)
            ->assertViewHas('activeCouriers', fn ($count) => $count >= 1)
            ->assertViewHas('monthlyRevenue', 300000.0)
            ->assertViewHas('monthlyPurchases', 125000.0)
            ->assertViewHas('pendingOrders', 1)
            ->assertViewHas('lowStockProducts', 1)
            ->assertViewHas('roleSummary', fn ($summary) => $summary['super-admin'] >= 1 && $summary['kasir'] >= 2);
    }

    public function test_purchasing_dashboard_uses_procurement_metrics(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $supplier = Supplier::factory()->create(['is_active' => true]);

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'received',
            'total' => 200000,
            'purchase_date' => now(),
        ]);

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'total' => 150000,
            'purchase_date' => now(),
        ]);

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'cancelled',
            'total' => 50000,
            'purchase_date' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('purchasing.dashboard')
            ->assertViewHas('activeSuppliers', 1)
            ->assertViewHas('todayPurchases', 350000.0)
            ->assertViewHas('todayTransactions', 2)
            ->assertViewHas('draftPurchases', 1)
            ->assertViewHas('receivedToday', 1)
            ->assertViewHas('receivedValueToday', 200000.0)
            ->assertViewHas('draftPurchaseValue', 150000.0)
            ->assertViewHas('statusSummary', [
                'draft' => 1,
                'received' => 1,
                'cancelled' => 1,
            ])
            ->assertViewHas('procurementTrend', fn ($trend) => $trend->count() === 6 && (float) $trend->last()['value'] === 200000.0);
    }

    public function test_admin_dashboard_uses_operational_database_metrics(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Sale::factory()->create([
            'status' => 'paid',
            'total' => 125000,
            'sale_date' => now(),
        ]);

        Sale::factory()->create([
            'status' => 'cancelled',
            'total' => 90000,
            'sale_date' => now(),
        ]);

        Product::factory()->create(['stock' => 4, 'is_active' => true]);
        Order::factory()->create(['status' => 'pending']);
        Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertViewHas('monthlyRevenue', 125000.0)
            ->assertViewHas('todayTransactions', 1)
            ->assertViewHas('activeProducts', 1)
            ->assertViewHas('lowStockProducts', 1)
            ->assertViewHas('pendingOrders', 1)
            ->assertViewHas('pendingPurchases', 1);
    }

    public function test_kasir_dashboard_uses_today_sales_and_open_shift_metrics(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $shift = \App\Models\CashierShift::factory()->create([
            'user_id' => $user->id,
            'status' => 'open',
            'opening_cash' => 100000,
        ]);

        Sale::factory()->create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'status' => 'paid',
            'payment_method' => 'cash',
            'total' => 150000,
            'sale_date' => now(),
        ]);

        Sale::factory()->create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'status' => 'cancelled',
            'payment_method' => 'cash',
            'total' => 50000,
            'sale_date' => now(),
        ]);

        Product::factory()->create(['stock' => 3, 'is_active' => true]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('kasir.dashboard')
            ->assertViewHas('todayRevenue', 150000.0)
            ->assertViewHas('todayTransactions', 1)
            ->assertViewHas('paymentSummary', fn ($summary) => (float) $summary['cash'] === 150000.0)
            ->assertViewHas('expectedCash', 250000.0)
            ->assertViewHas('lowStockProducts', fn ($products) => $products->count() === 1);
    }

    public function test_gudang_users_see_inventory_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);

        Product::factory()->create(['stock' => 4, 'is_active' => true]);
        Product::factory()->create(['stock' => 0, 'is_active' => true]);
        Product::factory()->create(['stock' => 30, 'is_active' => true]);
        Product::factory()->create(['stock' => 2, 'is_active' => false]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('gudang.dashboard')
            ->assertViewHas('activeProducts', 3)
            ->assertViewHas('totalStock', 34)
            ->assertViewHas('lowStockCount', 1)
            ->assertViewHas('outOfStockCount', 1)
            ->assertViewHas('inboundToday', 0)
            ->assertViewHas('outboundToday', 0);
    }

    public function test_kurir_users_see_the_kurir_dashboard(): void
    {
        $this->assertDashboardForRole('kurir', 'kurir.dashboard');
    }

    public function test_customer_users_see_the_customer_dashboard(): void
    {
        $this->assertDashboardForRole('customer', 'customer.dashboard');
    }

    public function test_super_admin_users_see_the_super_admin_dashboard(): void
    {
        $this->assertDashboardForRole('super-admin', 'super-admin.dashboard');
    }


    public function test_customer_dashboard_exposes_shopping_workspace_and_cart_count(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'workspace@maduramart.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'workspace@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'order_date' => now(),
        ]);

        $this->withSession([
            'customer_cart' => [
                10 => 2,
                20 => 3,
            ],
        ])->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('customer.dashboard')
            ->assertViewHas('cartItemCount', 5)
            ->assertSee(route('customer.catalog.index'), false)
            ->assertSee(route('customer.cart.index'), false)
            ->assertSee(route('customer.orders.index'), false)
            ->assertSee(route('customer.orders.show', $order), false);
    }

    public function test_customer_dashboard_is_scoped_to_authenticated_customer(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'buyer@maduramart.test',
            'name' => 'Buyer Madura',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'buyer@maduramart.test',
            'name' => 'Buyer Madura',
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'delivered',
            'total' => 125000,
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'shipped',
            'total' => 75000,
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'status' => 'delivered',
            'total' => 999999,
            'order_date' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('customer.dashboard')
            ->assertViewHas('totalOrders', 2)
            ->assertViewHas('activeOrders', 1)
            ->assertViewHas('completedOrders', 1)
            ->assertViewHas('cancelledOrders', 0)
            ->assertViewHas('totalSpent', 200000.0)
            ->assertViewHas('statusSummary', [
                'pending' => 0,
                'processing' => 0,
                'shipped' => 1,
                'delivered' => 1,
                'cancelled' => 0,
            ]);
    }
}

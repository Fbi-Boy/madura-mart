<?php

namespace Tests\Feature;

use App\Models\Courier;
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
            ->assertViewHas('deliveredToday', 1)
            ->assertViewHas('statusSummary', [
                'pending' => 0,
                'processing' => 0,
                'shipped' => 1,
                'delivered' => 1,
            ]);
    }

    public function test_purchasing_users_see_the_purchasing_dashboard(): void
    {
        $this->assertDashboardForRole('purchasing', 'purchasing.dashboard');
    }

    public function test_admin_users_see_the_admin_dashboard(): void
    {
        $this->assertDashboardForRole('admin', 'admin.dashboard');
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
            'purchase_date' => now()->subDay(),
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
            ->assertViewHas('statusSummary', [
                'draft' => 1,
                'received' => 1,
                'cancelled' => 1,
            ]);
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
}

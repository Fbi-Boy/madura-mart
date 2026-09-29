<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAwareNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_navigation_exposes_inventory_workspaces(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'gudang']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('gudang.stock-opname.index'), false)
            ->assertSee(route('gudang.penerimaan.index'), false)
            ->assertSee(route('gudang.barang-keluar.index'), false)
            ->assertSee(route('gudang.riwayat-stok.index'), false);
    }

    public function test_purchasing_navigation_exposes_procurement_workspaces(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'purchasing']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('purchasing.purchases.index'), false)
            ->assertSee(route('purchasing.suppliers.index'), false);
    }

    public function test_kurir_navigation_exposes_delivery_history(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kurir']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('kurir.pengiriman.riwayat'), false);
    }

    public function test_customer_navigation_exposes_shopping_workspaces(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('customer.catalog.index'), false)
            ->assertSee(route('customer.cart.index'), false)
            ->assertSee(route('customer.orders.index'), false)
            ->assertSee(route('customer.address.edit'), false);
    }

    public function test_admin_navigation_hides_a_permission_overridden_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);
        $this->view('layouts.navigation')
            ->assertSee(route('admin.monitoring.penjualan'), false);

        PermissionOverride::query()->create([
            'role' => 'admin',
            'permission' => 'system-monitoring.view',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->view('layouts.navigation')
            ->assertDontSee(route('admin.monitoring.penjualan'), false);
    }

    public function test_kasir_navigation_exposes_sales_workspaces_with_default_permission(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('kasir.transaksi-baru'), false)
            ->assertSee(route('kasir.riwayat-transaksi'), false)
            ->assertSee(route('kasir.retur'), false)
            ->assertSee(route('kasir.buka-shift'), false)
            ->assertSee(route('kasir.tutup-shift'), false)
            ->assertSee(route('kasir.riwayat-shift'), false);
    }


    public function test_kasir_navigation_hides_sales_workspaces_when_permission_is_revoked(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        PermissionOverride::query()->create([
            'role' => 'kasir',
            'permission' => 'sales.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user);
        $this->view('layouts.navigation')
            ->assertDontSee(route('kasir.transaksi-baru'), false)
            ->assertDontSee(route('kasir.riwayat-transaksi'), false)
            ->assertDontSee(route('kasir.retur'), false);
    }


    public function test_gudang_navigation_hides_inventory_workspaces_when_permission_is_revoked(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);

        PermissionOverride::query()->create([
            'role' => 'gudang',
            'permission' => 'stock.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->view('layouts.navigation')
            ->assertDontSee(route('gudang.stock-opname.index'), false)
            ->assertDontSee(route('gudang.penerimaan.index'), false)
            ->assertDontSee(route('gudang.barang-keluar.index'), false)
            ->assertDontSee(route('gudang.riwayat-stok.index'), false);
    }

    public function test_admin_navigation_hides_purchase_monitoring_when_purchase_permission_is_revoked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        PermissionOverride::query()->create([
            'role' => 'admin',
            'permission' => 'purchases.manage',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->view('layouts.navigation')
            ->assertDontSee(route('admin.monitoring.pembelian'), false)
            ->assertSee(route('admin.monitoring.penjualan'), false);
    }

    public function test_super_admin_navigation_hides_permission_control_links_when_overridden(): void
    {
        $admin = User::factory()->create(['role' => 'super-admin']);

        foreach ([
            'role-management.view' => 'admin.roles.index',
            'system-settings.view' => 'admin.settings.index',
            'activity-log.view' => 'admin.activity-logs.index',
            'audit-log.view' => 'admin.audit-logs.index',
            'system-monitoring.view' => 'admin.system-monitoring.index',
        ] as $permission => $route) {
            PermissionOverride::query()->create([
                'role' => 'super-admin',
                'permission' => $permission,
                'enabled' => false,
                'updated_by' => $admin->id,
            ]);
        }

        $this->actingAs($admin)
            ->view('layouts.navigation')
            ->assertDontSee(route('admin.roles.index'), false)
            ->assertDontSee(route('admin.settings.index'), false)
            ->assertDontSee(route('admin.activity-logs.index'), false)
            ->assertDontSee(route('admin.audit-logs.index'), false)
            ->assertDontSee(route('admin.system-monitoring.index'), false)
            ->assertSee(route('admin.users.index'), false);
    }

}

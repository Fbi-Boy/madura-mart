<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMonitoringPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_monitoring_and_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.monitoring.penjualan'))->assertOk();
        $this->actingAs($admin)->get(route('admin.monitoring.pesanan'))->assertOk();
        $this->actingAs($admin)->get(route('admin.report.stok'))->assertOk();
        $this->actingAs($admin)->get(route('admin.report.transaksi'))->assertOk();
    }

    public function test_admin_can_access_master_data_with_default_permissions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $routes = [
            'admin.categories.index',
            'admin.products.index',
            'admin.suppliers.index',
            'admin.customers.index',
            'admin.couriers.index',
            'admin.distributors.index',
            'admin.units.index',
            'admin.purchases.index',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_permission_override_can_block_admin_master_data_without_changing_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        PermissionOverride::create([
            'role' => 'admin',
            'permission' => 'customers.manage',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.customers.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk();
    }

    public function test_permission_overrides_can_block_monitoring_and_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        PermissionOverride::create([
            'role' => 'admin',
            'permission' => 'system-monitoring.view',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        PermissionOverride::create([
            'role' => 'admin',
            'permission' => 'reports.view',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.penjualan'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.report.stok'))
            ->assertForbidden();
    }

    public function test_permission_override_can_block_purchase_monitoring_without_changing_role(): void
    {
        $purchasing = User::factory()->create(['role' => 'purchasing']);

        PermissionOverride::create([
            'role' => 'purchasing',
            'permission' => 'purchases.manage',
            'enabled' => false,
            'updated_by' => $purchasing->id,
        ]);

        $this->actingAs($purchasing)
            ->get(route('admin.monitoring.pembelian'))
            ->assertForbidden();

        $this->actingAs($purchasing)
            ->get(route('purchasing.purchases.index'))
            ->assertForbidden();
    }

    public function test_non_admin_roles_cannot_enter_admin_monitoring_or_master_data(): void
    {
        $roles = ['customer', 'gudang', 'kasir', 'purchasing', 'kurir'];

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.monitoring.penjualan'))
                ->assertForbidden();

            $this->actingAs($user)
                ->get(route('admin.customers.index'))
                ->assertForbidden();
        }
    }
}

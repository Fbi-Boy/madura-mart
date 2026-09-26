<?php

namespace Tests\Feature;

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

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('admin.monitoring.penjualan'), false);

        \App\Models\PermissionOverride::query()->create([
            'role' => 'admin',
            'permission' => 'sales.manage',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee(route('admin.monitoring.penjualan'), false);
    }

}

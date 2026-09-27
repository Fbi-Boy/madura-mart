<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    use RefreshDatabase;
    public function test_guest_is_redirected_to_login_from_the_application_root(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_authenticated_user_is_redirected_to_the_dashboard_from_the_application_root(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('dashboard'));
    }

    public function test_guest_cannot_access_the_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_monitoring(): void
    {
        $this->get('/admin/monitoring/produk')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_sales_monitoring(): void
    {
        $this->get('/admin/monitoring/penjualan')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_purchase_monitoring(): void
    {
        $this->get('/admin/monitoring/pembelian')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_reports(): void
    {
        $this->get('/admin/report/stok')->assertRedirect('/login');
    }

    public function test_admin_monitoring_is_denied_to_operational_roles(): void
    {
        foreach (['gudang', 'kasir', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/admin/monitoring/produk')
                ->assertForbidden();
        }
    }

    public function test_purchasing_workspace_is_denied_to_other_roles(): void
    {
        foreach (['admin', 'gudang', 'kasir', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/purchasing/purchases')
                ->assertForbidden();
        }
    }

    public function test_warehouse_workspace_is_denied_to_other_roles(): void
    {
        foreach (['admin', 'kasir', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/gudang/stock-opname')
                ->assertForbidden();
        }
    }

    public function test_cashier_workspace_is_denied_to_other_roles(): void
    {
        foreach (['admin', 'gudang', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/kasir/riwayat-transaksi')
                ->assertForbidden();
        }
    }

    public function test_courier_workspace_is_denied_to_other_roles(): void
    {
        foreach (['admin', 'gudang', 'kasir', 'purchasing', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/kurir/pengiriman')
                ->assertForbidden();
        }
    }

    public function test_customer_workspace_is_denied_to_staff_roles(): void
    {
        foreach (['admin', 'gudang', 'kasir', 'purchasing', 'kurir'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/customer/catalog')
                ->assertForbidden();
        }
    }

    public function test_guest_cannot_access_the_profile_page(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_guest_cannot_update_the_profile(): void
    {
        $this->patch('/profile', [])->assertRedirect('/login');
    }
}

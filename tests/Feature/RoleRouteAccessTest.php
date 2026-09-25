<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRouteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_monitoring_routes(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/penjualan')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_report_routes(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/report/stok')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_routes(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/transaksi-baru')
            ->assertForbidden();
    }

    public function test_cashier_cannot_access_admin_monitoring_routes(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($user)
            ->get('/admin/monitoring/produk')
            ->assertForbidden();
    }
    public function test_customer_cannot_access_admin_monitoring_pembelian(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/pembelian')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_monitoring_pesanan(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/pesanan')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_monitoring_produk(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/produk')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_monitoring_distributor(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/distributor')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_monitoring_client(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/client')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_monitoring_kurir(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/monitoring/kurir')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_report_penjualan(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/report/penjualan')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_report_pembelian(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/report/pembelian')
            ->assertForbidden();
    }

    public function test_customer_cannot_access_admin_report_stok(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/report/stok')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_transaction_history(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/riwayat-transaksi')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_returns(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/retur')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_open_shift(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/buka-shift')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_close_shift(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/tutup-shift')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_cashier_shift_history(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/kasir/riwayat-shift')
            ->assertForbidden();
    }

    public function test_super_admin_can_access_admin_monitoring(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get('/admin/monitoring/penjualan')
            ->assertOk();
    }

    public function test_super_admin_can_access_admin_reports(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get('/admin/report/penjualan')
            ->assertOk();
    }

    public function test_purchasing_can_access_purchase_monitoring(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $this->actingAs($user)
            ->get('/admin/monitoring/pembelian')
            ->assertOk();
    }

    public function test_purchasing_cannot_access_other_admin_monitoring(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $this->actingAs($user)
            ->get('/admin/monitoring/penjualan')
            ->assertForbidden();
    }


}

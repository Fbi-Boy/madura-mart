<?php

namespace Tests\Feature\Admin;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_purchases_and_search_invoice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Purchase::factory()->create(['invoice' => 'PO-001', 'purchase_date' => now(), 'total' => 125000]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.pembelian', ['search' => 'PO-001']))
            ->assertOk()
            ->assertViewHas('todayTransactions', 1)
            ->assertViewHas('todayPurchases', 125000.0);
    }

    public function test_kasir_cannot_monitor_purchases(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.pembelian'))
            ->assertForbidden();
    }
}
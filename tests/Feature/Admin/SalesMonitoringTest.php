<?php

namespace Tests\Feature\Admin;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_sales_and_search_invoice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sale::factory()->create(['invoice' => 'INV-001', 'status' => 'paid', 'sale_date' => now(), 'total' => 75000]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.penjualan', ['search' => 'INV-001']))
            ->assertOk()
            ->assertViewHas('todayTransactions', 1)
            ->assertViewHas('todaySales', 75000.0);
    }

    public function test_kasir_cannot_monitor_sales(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.penjualan'))
            ->assertForbidden();
    }
}
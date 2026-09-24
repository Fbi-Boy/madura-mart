<?php

namespace Tests\Feature\Admin;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_sales_report_by_period(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sale::factory()->create(['sale_date' => '2026-09-10 10:00:00', 'status' => 'paid', 'total' => 50000]);
        Sale::factory()->create(['sale_date' => '2026-08-10 10:00:00', 'status' => 'paid', 'total' => 25000]);

        $this->actingAs($admin)
            ->get(route('admin.report.penjualan', ['from' => '2026-09-01', 'to' => '2026-09-30']))
            ->assertOk()
            ->assertViewHas('totalTransactions', 1)
            ->assertViewHas('totalSales', 50000.0);
    }

    public function test_kasir_cannot_access_sales_report(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.report.penjualan'))
            ->assertForbidden();
    }
}
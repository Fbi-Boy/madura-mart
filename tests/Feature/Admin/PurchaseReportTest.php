<?php

namespace Tests\\Feature\\Admin;

use App\\Models\\Purchase;
use App\\Models\\Supplier;
use App\\Models\\User;
use Illuminate\\Foundation\\Testing\\RefreshDatabase;
use Tests\\TestCase;

class PurchaseReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_purchase_report_uses_filtered_database_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'received',
            'total' => 125000,
            'purchase_date' => '2026-09-10 10:00:00',
        ]);

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'total' => 75000,
            'purchase_date' => '2026-09-12 10:00:00',
        ]);

        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'cancelled',
            'total' => 999000,
            'purchase_date' => '2026-08-20 10:00:00',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.report.pembelian', ['from' => '2026-09-01', 'to' => '2026-09-30']))
            ->assertOk()
            ->assertViewIs('admin.report.pembelian.index')
            ->assertViewHas('totalTransactions', 2)
            ->assertViewHas('totalPurchases', 200000.0)
            ->assertViewHas('receivedTransactions', 1)
            ->assertViewHas('draftTransactions', 1);
    }

    public function test_non_admin_cannot_access_purchase_report(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $this->actingAs($user)
            ->get(route('admin.report.pembelian'))
            ->assertForbidden();
    }
}

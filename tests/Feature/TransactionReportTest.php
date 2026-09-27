<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_transaction_report_with_summary(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Sale::factory()->create(['status' => 'paid', 'total' => 125000, 'sale_date' => now()]);
        Sale::factory()->create(['status' => 'paid', 'total' => 75000, 'sale_date' => now()]);
        Sale::factory()->create(['status' => 'cancelled', 'total' => 500000, 'sale_date' => now()]);

        $this->actingAs($user)
            ->get(route('admin.report.transaksi'))
            ->assertOk()
            ->assertViewIs('admin.report.transaksi.index')
            ->assertViewHas('summary', [
                'all' => 3,
                'paid' => 2,
                'cancelled' => 1,
                'gross' => 200000.0,
            ]);
    }

    public function test_transaction_report_can_filter_status(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Sale::factory()->create(['status' => 'paid', 'sale_date' => now()]);
        Sale::factory()->create(['status' => 'cancelled', 'sale_date' => now()]);

        $this->actingAs($user)
            ->get(route('admin.report.transaksi', ['status' => 'paid']))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) =>
                $transactions->total() === 1
                && $transactions->first()->status === 'paid'
            );
    }

    public function test_transaction_report_rejects_operational_roles(): void
    {
        foreach (['customer', 'kasir', 'gudang', 'purchasing', 'kurir'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.report.transaksi'))
                ->assertForbidden();
        }
    }

    public function test_super_admin_can_view_transaction_report(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get(route('admin.report.transaksi'))
            ->assertOk();
    }

    public function test_guest_cannot_view_transaction_report(): void
    {
        $this->get(route('admin.report.transaksi'))
            ->assertRedirect(route('login'));
    }
}

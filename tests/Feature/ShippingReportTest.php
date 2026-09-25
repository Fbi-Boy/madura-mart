<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_shipping_report_metrics(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $courier = Courier::factory()->create(['is_active' => true]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'delivered',
            'total' => 250000,
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
            'total' => 150000,
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'courier_id' => null,
            'status' => 'pending',
            'total' => 100000,
            'order_date' => now(),
        ]);

        Order::factory()->create([
            'status' => 'cancelled',
            'total' => 900000,
            'order_date' => now()->subMonth(),
        ]);

        $this->actingAs($user)
            ->get('/admin/report/pengiriman?from='.now()->startOfMonth()->format('Y-m-d').'&to='.now()->endOfMonth()->format('Y-m-d'))
            ->assertOk()
            ->assertViewIs('admin.report.pengiriman.index')
            ->assertViewHas('totalShipments', 3)
            ->assertViewHas('assignedShipments', 2)
            ->assertViewHas('shippingValue', 500000.0)
            ->assertViewHas('statusSummary', [
                'pending' => 1,
                'processing' => 0,
                'shipped' => 1,
                'delivered' => 1,
                'cancelled' => 0,
            ]);
    }

    public function test_shipping_report_is_restricted_to_admin_roles(): void
    {
        foreach (['admin', 'super-admin'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get('/admin/report/pengiriman')
                ->assertOk();
        }

        foreach (['purchasing', 'gudang', 'kasir', 'kurir', 'customer'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get('/admin/report/pengiriman')
                ->assertForbidden();
        }

        $this->get('/admin/report/pengiriman')->assertRedirect('/login');
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Order::factory()->create([
            'order_number' => 'ORD-001',
            'order_date' => now(),
            'total' => 125000,
            'status' => 'processing',
        ]);
        Order::factory()->create([
            'order_number' => 'ORD-002',
            'order_date' => now(),
            'total' => 50000,
            'status' => 'delivered',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.pesanan', ['search' => 'ORD-001']))
            ->assertOk()
            ->assertViewHas('totalOrders', 2)
            ->assertViewHas('processing', 1)
            ->assertViewHas('delivered', 1)
            ->assertViewHas('todayTotal', 175000.0)
            ->assertSee('ORD-001')
            ->assertDontSee('ORD-002');
    }

    public function test_admin_can_search_order_by_customer_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create();
        Order::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.monitoring.pesanan', ['search' => $order->customer->name]))
            ->assertOk()
            ->assertSee($order->order_number);
    }

    public function test_kasir_cannot_monitor_orders(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.pesanan'))
            ->assertForbidden();
    }
}

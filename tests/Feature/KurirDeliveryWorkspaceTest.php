<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurirDeliveryWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurir_can_view_only_its_active_delivery_tasks(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'workspace-kurir@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'workspace-kurir@maduramart.test',
            'is_active' => true,
        ]);

        $assigned = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'processing',
            'order_number' => 'MM-WORKSPACE-001',
        ]);

        Order::factory()->create([
            'status' => 'shipped',
            'order_number' => 'MM-WORKSPACE-OTHER',
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'delivered',
            'order_number' => 'MM-WORKSPACE-DONE',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.index'))
            ->assertOk()
            ->assertViewIs('kurir.tugas')
            ->assertViewHas('summary', [
                'all' => 1,
                'pending' => 0,
                'processing' => 1,
                'shipped' => 0,
            ])
            ->assertSee($assigned->order_number)
            ->assertDontSee('MM-WORKSPACE-OTHER')
            ->assertDontSee('MM-WORKSPACE-DONE');
    }

    public function test_kurir_can_filter_active_tasks_by_status(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'filter-kurir@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'filter-kurir@maduramart.test',
            'is_active' => true,
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'pending',
            'order_number' => 'MM-FILTER-PENDING',
        ]);

        $shipped = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
            'order_number' => 'MM-FILTER-SHIPPED',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.index', ['status' => 'shipped']))
            ->assertOk()
            ->assertViewHas('status', 'shipped')
            ->assertViewHas('orders', fn ($orders) => $orders->count() === 1 && $orders->first()->id === $shipped->id)
            ->assertSee('MM-FILTER-SHIPPED')
            ->assertDontSee('MM-FILTER-PENDING');
    }

    public function test_kurir_can_open_detail_for_assigned_order(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'detail-kurir@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'detail-kurir@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
            'order_number' => 'MM-DETAIL-001',
            'delivery_address' => 'Jl. Madura No. 10',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.show', $order))
            ->assertOk()
            ->assertViewIs('kurir.tugas-show')
            ->assertViewHas('order', fn ($loaded) => $loaded->id === $order->id)
            ->assertSee('MM-DETAIL-001')
            ->assertSee('Jl. Madura No. 10')
            ->assertSee('Tandai Selesai');
    }

    public function test_kurir_cannot_open_another_couriers_delivery_detail(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'owner-kurir@maduramart.test',
        ]);

        Courier::factory()->create([
            'email' => 'owner-kurir@maduramart.test',
            'is_active' => true,
        ]);

        $otherCourier = Courier::factory()->create([
            'email' => 'other-kurir@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $otherCourier->id,
            'status' => 'shipped',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.show', $order))
            ->assertForbidden();
    }

    public function test_non_kurir_cannot_access_delivery_task_workspace(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.index'))
            ->assertForbidden();
    }
}

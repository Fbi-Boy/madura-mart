<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurirDeliveryHistoryWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurir_history_supports_status_and_customer_search_filters(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'history-kurir@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'history-kurir@maduramart.test',
            'is_active' => true,
        ]);

        $customer = \App\Models\Customer::factory()->create(['name' => 'Budi History']);

        $matching = Order::factory()->create([
            'courier_id' => $courier->id,
            'customer_id' => $customer->id,
            'status' => 'delivered',
            'order_number' => 'MM-HISTORY-001',
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'cancelled',
            'order_number' => 'MM-HISTORY-002',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.riwayat', [
                'status' => 'delivered',
                'q' => 'Budi History',
            ]))
            ->assertOk()
            ->assertViewIs('kurir.riwayat')
            ->assertViewHas('summary', [
                'all' => 2,
                'delivered' => 1,
                'cancelled' => 1,
            ])
            ->assertViewHas('orders', fn ($orders) => $orders->count() === 1 && $orders->first()->id === $matching->id)
            ->assertSee('MM-HISTORY-001')
            ->assertDontSee('MM-HISTORY-002');
    }

    public function test_kurir_history_isolated_from_other_couriers(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'isolated-history@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'isolated-history@maduramart.test',
            'is_active' => true,
        ]);

        $otherCourier = Courier::factory()->create([
            'email' => 'other-history@maduramart.test',
            'is_active' => true,
        ]);

        $ownOrder = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'delivered',
            'order_number' => 'MM-OWN-HISTORY',
        ]);

        Order::factory()->create([
            'courier_id' => $otherCourier->id,
            'status' => 'delivered',
            'order_number' => 'MM-OTHER-HISTORY',
        ]);

        ActivityLog::query()->create([
            'user_id' => $user->id,
            'action' => 'delivery.status_updated',
            'subject_type' => Order::class,
            'subject_id' => $ownOrder->id,
            'description' => 'Pengiriman milik kurir ini selesai.',
        ]);

        $otherOrder = Order::query()->where('order_number', 'MM-OTHER-HISTORY')->firstOrFail();

        ActivityLog::query()->create([
            'user_id' => $user->id,
            'action' => 'delivery.status_updated',
            'subject_type' => Order::class,
            'subject_id' => $otherOrder->id,
            'description' => 'Aktivitas kurir lain.',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.riwayat'))
            ->assertOk()
            ->assertSee('MM-OWN-HISTORY')
            ->assertDontSee('MM-OTHER-HISTORY')
            ->assertSee('Pengiriman milik kurir ini selesai.')
            ->assertDontSee('Aktivitas kurir lain.');
    }
}

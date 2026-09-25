<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurirDeliveryHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurir_can_view_only_their_completed_delivery_history(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'kurir-history@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'kurir-history@maduramart.test',
            'is_active' => true,
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'delivered',
            'order_number' => 'ORD-HISTORY-1',
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'cancelled',
            'order_number' => 'ORD-HISTORY-2',
        ]);

        Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'shipped',
            'order_number' => 'ORD-ACTIVE',
        ]);

        Order::factory()->create([
            'status' => 'delivered',
            'order_number' => 'ORD-OTHER-COURIER',
        ]);

        $this->actingAs($user)
            ->get('/kurir/pengiriman/riwayat')
            ->assertOk()
            ->assertViewIs('kurir.riwayat')
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 2)
            ->assertSee('ORD-HISTORY-1')
            ->assertSee('ORD-HISTORY-2')
            ->assertDontSee('ORD-ACTIVE')
            ->assertDontSee('ORD-OTHER-COURIER');
    }

    public function test_non_kurir_cannot_access_delivery_history(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/kurir/pengiriman/riwayat')
            ->assertForbidden();
    }
}

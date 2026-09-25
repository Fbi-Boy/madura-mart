<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierDeliveryStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_courier_can_advance_its_assigned_delivery_status(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'kurir-status@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'kurir-status@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('kurir.pengiriman.status', $order), ['status' => 'processing'])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_courier_cannot_skip_delivery_status(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'kurir-skip@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => 'kurir-skip@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('kurir.pengiriman.status', $order), ['status' => 'delivered'])
            ->assertStatus(422);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_courier_cannot_update_another_couriers_delivery(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'kurir-one@maduramart.test',
        ]);

        $otherCourier = Courier::factory()->create([
            'email' => 'kurir-two@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $otherCourier->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('kurir.pengiriman.status', $order), ['status' => 'processing'])
            ->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }
}

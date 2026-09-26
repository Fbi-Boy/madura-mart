<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_order_detail_exposes_status_tracking_stages(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'tracking@maduramart.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'tracking@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'shipped',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertViewIs('customer.orders.show')
            ->assertViewHas('currentStatusIndex', 2)
            ->assertViewHas('trackingSteps', fn ($steps) =>
                count($steps) === 4
                && $steps[0]['key'] === 'pending'
                && $steps[2]['key'] === 'shipped'
                && $steps[3]['key'] === 'delivered'
            )
            ->assertSee('Perjalanan Pesanan')
            ->assertSee('Dalam pengiriman')
            ->assertSee('Timeline menunjukkan tahapan status');
    }

    public function test_customer_order_tracking_does_not_expose_another_customers_order(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'owner@maduramart.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'owner@maduramart.test',
            'is_active' => true,
        ]);

        $otherCustomer = Customer::factory()->create([
            'email' => 'other@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'status' => 'shipped',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.show', $order))
            ->assertNotFound();
    }

    public function test_cancelled_order_tracking_adds_cancelled_stage(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'cancelled@maduramart.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'cancelled@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'cancelled',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertViewHas('currentStatusIndex', false)
            ->assertViewHas('trackingSteps', fn ($steps) =>
                count($steps) === 5
                && $steps[4]['key'] === 'cancelled'
            )
            ->assertSee('Pesanan dibatalkan');
    }
}

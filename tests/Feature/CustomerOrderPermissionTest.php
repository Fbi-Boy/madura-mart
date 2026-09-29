<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_with_orders_permission_can_access_order_workspace(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'orders@maduramart.test',
        ]);

        Customer::factory()->create([
            'email' => 'orders@maduramart.test',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.index'))
            ->assertOk();
    }

    public function test_customer_order_workspace_is_blocked_when_permission_is_disabled(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'blocked@maduramart.test',
        ]);

        Customer::factory()->create([
            'email' => 'blocked@maduramart.test',
            'is_active' => true,
        ]);

        PermissionOverride::create([
            'role' => 'customer',
            'permission' => 'orders.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.index'))
            ->assertForbidden();
    }

    public function test_customer_payment_workspace_is_blocked_when_orders_permission_is_disabled(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'payment-blocked@maduramart.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => 'payment-blocked@maduramart.test',
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        PermissionOverride::create([
            'role' => 'customer',
            'permission' => 'orders.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('customer.payment.show', $order))
            ->assertForbidden();
    }
}

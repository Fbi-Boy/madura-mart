<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_only_their_order_history_and_detail(): void
    {
        $email = 'buyer@maduramart.test';
        $user = User::factory()->create(['role' => 'customer', 'email' => $email]);
        $customer = Customer::factory()->create(['email' => $email, 'is_active' => true]);

        $product = Product::factory()->create(['name' => 'Beras Madura', 'price' => 15000, 'stock' => 10]);

        $ownOrder = Order::factory()->create([
            'customer_id' => $customer->id,
            'total' => 30000,
            'status' => 'pending',
        ]);

        OrderItem::factory()->create([
            'order_id' => $ownOrder->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 15000,
            'subtotal' => 30000,
        ]);

        $otherCustomer = Customer::factory()->create(['is_active' => true]);
        Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'total' => 999000,
            'status' => 'delivered',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.index'))
            ->assertOk()
            ->assertViewIs('customer.orders.index')
            ->assertViewHas('orders', fn ($orders) =>
                $orders->total() === 1
                && $orders->first()->id === $ownOrder->id
            );

        $this->actingAs($user)
            ->get(route('customer.orders.show', $ownOrder))
            ->assertOk()
            ->assertViewIs('customer.orders.show')
            ->assertViewHas('order', fn ($order) =>
                $order->id === $ownOrder->id
                && $order->items->count() === 1
            );
    }

    public function test_customer_cannot_open_another_customers_order_detail(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'buyer@maduramart.test',
        ]);
        $customer = Customer::factory()->create([
            'email' => 'buyer@maduramart.test',
            'is_active' => true,
        ]);
        $otherCustomer = Customer::factory()->create(['is_active' => true]);

        $order = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.show', $order))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $otherCustomer->id,
        ]);

        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }
}

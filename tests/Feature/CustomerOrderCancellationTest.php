<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'cancel@maduramart.test',
        ]);
        $customer = Customer::factory()->create([
            'email' => 'cancel@maduramart.test',
            'is_active' => true,
        ]);
        $product = Product::factory()->create([
            'stock' => 3,
            'price' => 15000,
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 15000,
            'subtotal' => 30000,
        ]);
        $product->decrement('stock', 2);

        $this->actingAs($user)
            ->patch(route('customer.orders.cancel', $order))
            ->assertRedirect(route('customer.orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity' => 2,
            'type' => 'return',
            'reference_type' => 'order_cancellation',
            'reference_id' => $order->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_customer_cannot_cancel_paid_order(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'paid@maduramart.test',
        ]);
        $customer = Customer::factory()->create([
            'email' => 'paid@maduramart.test',
            'is_active' => true,
        ]);
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $this->actingAs($user)
            ->patch(route('customer.orders.cancel', $order))
            ->assertUnprocessable();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_customer_cannot_cancel_order_after_processing_started(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'processing@maduramart.test',
        ]);
        $customer = Customer::factory()->create([
            'email' => 'processing@maduramart.test',
            'is_active' => true,
        ]);
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'processing',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('customer.orders.cancel', $order))
            ->assertUnprocessable();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_customer_cannot_cancel_another_customers_order(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'owner@maduramart.test',
        ]);
        Customer::factory()->create([
            'email' => 'owner@maduramart.test',
            'is_active' => true,
        ]);
        $otherCustomer = Customer::factory()->create(['is_active' => true]);
        $order = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('customer.orders.cancel', $order))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function customerUser(): array
    {
        $email = fake()->unique()->safeEmail();
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => $email,
        ]);

        $customer = Customer::factory()->create([
            'email' => $email,
            'is_active' => true,
            'address' => 'Jl. Madura 1',
            'city' => 'Jember',
        ]);

        return [$user, $customer];
    }

    public function test_customer_can_create_order_from_cart_and_stock_is_decremented(): void
    {
        [$user, $customer] = $this->customerUser();
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 7,
            'price' => 12500,
        ]);

        $response = $this->withSession([
            'customer_cart' => [$product->id => 3],
        ])->actingAs($user)->post(route('customer.checkout.store'));

        $order = Order::query()->where('customer_id', $customer->id)->first();

        $response
            ->assertRedirect(route('customer.checkout.success', $order));

        $this->assertNotNull($order);
        $this->assertSame('pending', $order->status);
        $this->assertSame(37500.0, (float) $order->total);
        $this->assertSame(1, $order->items()->count());
        $this->assertSame(3, $order->items()->first()->quantity);
        $this->assertSame(4, $product->fresh()->stock);
        $this->assertNull(session('customer_cart'));
    }

    public function test_checkout_rejects_insufficient_stock_without_creating_order(): void
    {
        [$user, $customer] = $this->customerUser();
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 2,
            'price' => 10000,
        ]);

        $this->withSession([
            'customer_cart' => [$product->id => 3],
        ])->actingAs($user)
            ->post(route('customer.checkout.store'))
            ->assertStatus(422);

        $this->assertDatabaseMissing('orders', ['customer_id' => $customer->id]);
        $this->assertSame(2, $product->fresh()->stock);
        $this->assertSame([$product->id => 3], session('customer_cart'));
    }

    public function test_customer_can_only_open_own_order_confirmation(): void
    {
        [$user] = $this->customerUser();
        [$otherUser, $otherCustomer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('customer.checkout.success', $order))
            ->assertForbidden();
    }
}

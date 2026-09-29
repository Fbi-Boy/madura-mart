<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PermissionOverride;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCheckoutPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_with_orders_permission_can_access_checkout(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'checkout@maduramart.test',
        ]);

        Customer::factory()->create([
            'email' => 'checkout@maduramart.test',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('customer.checkout.index'))
            ->assertOk();
    }

    public function test_customer_checkout_is_blocked_when_orders_permission_is_disabled(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'checkout-blocked@maduramart.test',
        ]);

        Customer::factory()->create([
            'email' => 'checkout-blocked@maduramart.test',
            'is_active' => true,
        ]);

        PermissionOverride::create([
            'role' => 'customer',
            'permission' => 'orders.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('customer.checkout.index'))
            ->assertForbidden();
    }

    public function test_customer_checkout_submission_is_blocked_when_orders_permission_is_disabled(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'checkout-post-blocked@maduramart.test',
        ]);

        Customer::factory()->create([
            'email' => 'checkout-post-blocked@maduramart.test',
            'is_active' => true,
        ]);

        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 10,
            'price' => 15000,
        ]);

        $user->forceFill([])->save();

        PermissionOverride::create([
            'role' => 'customer',
            'permission' => 'orders.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->withSession(['customer_cart' => [$product->id => 1]])
            ->post(route('customer.checkout.store'), [
                'payment_method' => 'qris',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10,
        ]);
    }
}

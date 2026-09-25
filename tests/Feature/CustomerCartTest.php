<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_available_product_to_cart(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 5,
            'price' => 10000,
        ]);

        $this->actingAs($user)
            ->post(route('customer.cart.add', $product), ['quantity' => 2])
            ->assertRedirect();

        $this->assertSame(
            ['' . $product->id . '' => 2],
            session('customer_cart')
        );
    }

    public function test_customer_cart_quantity_cannot_exceed_stock(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 3,
            'price' => 10000,
        ]);

        $this->actingAs($user)
            ->post(route('customer.cart.add', $product), ['quantity' => 5])
            ->assertRedirect();

        $this->assertSame(3, session('customer_cart')[$product->id]);
    }

    public function test_customer_can_update_and_remove_cart_item(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 8,
            'price' => 15000,
        ]);

        $this->withSession(['customer_cart' => [$product->id => 2]])
            ->actingAs($user)
            ->patch(route('customer.cart.update'), [
                'product_id' => $product->id,
                'quantity' => 4,
            ])
            ->assertRedirect();

        $this->assertSame(4, session('customer_cart')[$product->id]);

        $this->actingAs($user)
            ->delete(route('customer.cart.remove'), ['product_id' => $product->id])
            ->assertRedirect();

        $this->assertSame([], session('customer_cart'));
    }

    public function test_non_customer_cannot_access_cart(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($user)
            ->get(route('customer.cart.index'))
            ->assertForbidden();
    }

    public function test_cart_summary_uses_current_product_price_and_quantity(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'is_active' => true,
            'stock' => 5,
            'price' => 12500,
        ]);

        $this->withSession(['customer_cart' => [$product->id => 3]])
            ->actingAs($user)
            ->get(route('customer.cart.index'))
            ->assertOk()
            ->assertViewIs('customer.cart.index')
            ->assertViewHas('total', 37500.0)
            ->assertViewHas('items', fn ($items) => $items->count() === 1
                && $items->first()['quantity'] === 3);
    }
}

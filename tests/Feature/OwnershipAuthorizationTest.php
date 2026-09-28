<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OwnershipAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_view_another_customers_order(): void
    {
        [$owner, $other, $order] = $this->customerOrderPair();

        $this->actingAs($other)
            ->get(route('customer.orders.show', $order))
            ->assertNotFound();

        $this->actingAs($owner)
            ->get(route('customer.orders.show', $order))
            ->assertOk();
    }

    public function test_customer_cannot_cancel_another_customers_order(): void
    {
        [$owner, $other, $order] = $this->customerOrderPair();

        $this->actingAs($other)
            ->patch(route('customer.orders.cancel', $order))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);

        $this->actingAs($owner)
            ->patch(route('customer.orders.cancel', $order))
            ->assertRedirect(route('customer.orders.show', $order));
    }

    public function test_customer_cannot_access_another_customers_payment(): void
    {
        [$owner, $other, $order] = $this->customerOrderPair();

        $this->actingAs($other)
            ->get(route('customer.payment.show', $order))
            ->assertForbidden();

        Storage::fake('local');

        $this->actingAs($other)
            ->post(route('customer.payment.store', $order), [
                'payment_proof' => UploadedFile::fake()->create('proof.pdf', 10, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
            'payment_proof' => 'payment-proofs/proof.pdf',
        ]);
    }

    public function test_customer_can_only_see_own_orders_in_index(): void
    {
        [$owner, $other, $order, $ownerCustomer, $otherCustomer] = $this->customerOrderPair();

        $otherOrder = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'order_number' => 'ORD-OTHER-CUSTOMER',
        ]);

        $this->actingAs($owner)
            ->get(route('customer.orders.index'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertDontSee($otherOrder->order_number);
    }

    public function test_courier_cannot_view_another_couriers_order(): void
    {
        $first = User::factory()->create([
            'role' => 'kurir',
            'email' => 'courier-one@maduramart.test',
        ]);
        $second = User::factory()->create([
            'role' => 'kurir',
            'email' => 'courier-two@maduramart.test',
        ]);

        $firstCourier = Courier::factory()->create([
            'email' => $first->email,
            'is_active' => true,
        ]);
        $secondCourier = Courier::factory()->create([
            'email' => $second->email,
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $secondCourier->id,
            'status' => 'pending',
        ]);

        $this->actingAs($first)
            ->get(route('kurir.pengiriman.show', $order))
            ->assertForbidden();

        $this->actingAs($first)
            ->patch(route('kurir.pengiriman.status', $order), [
                'status' => 'processing',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'courier_id' => $secondCourier->id,
            'status' => 'pending',
        ]);

        $this->assertNotSame($firstCourier->id, $secondCourier->id);
    }

    public function test_courier_can_update_only_their_assigned_order(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'courier-assigned@maduramart.test',
        ]);

        $courier = Courier::factory()->create([
            'email' => $user->email,
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'courier_id' => $courier->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('kurir.pengiriman.status', $order), [
                'status' => 'processing',
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    private function customerOrderPair(): array
    {
        $owner = User::factory()->create([
            'role' => 'customer',
            'email' => 'owner@maduramart.test',
        ]);
        $other = User::factory()->create([
            'role' => 'customer',
            'email' => 'other@maduramart.test',
        ]);

        $ownerCustomer = Customer::factory()->create([
            'email' => $owner->email,
            'is_active' => true,
        ]);
        $otherCustomer = Customer::factory()->create([
            'email' => $other->email,
            'is_active' => true,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $ownerCustomer->id,
            'order_number' => 'ORD-OWNER',
        ]);

        return [$owner, $other, $order, $ownerCustomer, $otherCustomer];
    }
}

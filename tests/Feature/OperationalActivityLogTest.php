<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationalActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_creation_and_cancellation_are_logged(): void
    {
        $user = User::factory()->create([
            'role' => 'purchasing',
            'email' => 'purchasing@maduramart.test',
        ]);
        $supplier = Supplier::factory()->create(['is_active' => true]);
        $product = Product::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->post(route('purchasing.purchases.store'), [
                'invoice' => 'PO-AUDIT-001',
                'supplier_id' => $supplier->id,
                'purchase_date' => now()->toDateString(),
                'items' => [[
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 25000,
                ]],
            ])
            ->assertRedirect(route('purchasing.purchases.index'));

        $purchase = Purchase::query()->where('invoice', 'PO-AUDIT-001')->firstOrFail();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'purchase.created',
            'subject_id' => $purchase->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->patch(route('purchasing.purchases.cancel', $purchase))
            ->assertRedirect(route('purchasing.purchases.index'));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'purchase.cancelled',
            'subject_id' => $purchase->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_customer_order_cancellation_is_logged(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'buyer-audit@maduramart.test',
        ]);
        $customer = Customer::factory()->create([
            'email' => 'buyer-audit@maduramart.test',
            'is_active' => true,
        ]);
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch(route('customer.orders.cancel', $order))
            ->assertRedirect(route('customer.orders.show', $order));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'order.cancelled',
            'subject_id' => $order->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_courier_status_changes_are_logged(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'courier-audit@maduramart.test',
        ]);
        $courier = Courier::factory()->create([
            'email' => 'courier-audit@maduramart.test',
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

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'delivery.status_updated',
            'subject_id' => $order->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_stock_opname_completion_is_logged(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create([
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('gudang.stock-opname.store'), [
                'notes' => 'Audit stok pagi',
                'actual_stock' => [$product->id => 8],
            ])
            ->assertRedirect(route('gudang.stock-opname.index'));

        $opname = \App\Models\StockOpname::query()->latest('id')->firstOrFail();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'stock_opname.completed',
            'subject_id' => $opname->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }
}

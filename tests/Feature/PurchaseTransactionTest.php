<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_purchase_and_increase_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($admin)->post(route('admin.purchases.store'), [
            'invoice' => 'PO-TEST-001',
            'supplier_id' => $supplier->id,
            'purchase_date' => '2026-09-24',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 5,
                'unit_price' => 12000,
            ]],
        ])->assertRedirect(route('admin.purchases.index'));

        $this->assertDatabaseHas('purchases', [
            'invoice' => 'PO-TEST-001',
            'total' => 60000,
            'status' => 'received',
        ]);
        $this->assertDatabaseHas('purchase_items', [
            'product_id' => $product->id,
            'quantity' => 5,
            'subtotal' => 60000,
        ]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 15]);
    }

    public function test_purchase_rejects_inactive_supplier(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create(['is_active' => false]);
        $product = Product::factory()->create();

        $this->actingAs($admin)->post(route('admin.purchases.store'), [
            'invoice' => 'PO-TEST-003',
            'supplier_id' => $supplier->id,
            'purchase_date' => '2026-09-24',
            'items' => [['product_id' => $product->id, 'quantity' => 1, 'unit_price' => 1000]],
        ])->assertSessionHasErrors('supplier_id');
    }

    public function test_purchase_requires_at_least_one_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();

        $this->actingAs($admin)->post(route('admin.purchases.store'), [
            'invoice' => 'PO-TEST-002',
            'supplier_id' => $supplier->id,
            'purchase_date' => '2026-09-24',
            'items' => [],
        ])->assertSessionHasErrors('items');
    }
    public function test_purchasing_can_create_draft_purchase_order_without_increasing_stock(): void
    {
        $purchasing = User::factory()->create(['role' => 'purchasing']);
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($purchasing)->post(route('purchasing.purchases.store'), [
            'invoice' => 'PO-DRAFT-001',
            'supplier_id' => $supplier->id,
            'purchase_date' => '2026-09-25',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 5,
                'unit_price' => 12000,
            ]],
        ])->assertRedirect(route('purchasing.purchases.index'));

        $this->assertDatabaseHas('purchases', [
            'invoice' => 'PO-DRAFT-001',
            'total' => 60000,
            'status' => 'draft',
            'user_id' => $purchasing->id,
        ]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 10]);
    }

    public function test_purchasing_can_cancel_only_draft_purchase_orders(): void
    {
        $purchasing = User::factory()->create(['role' => 'purchasing']);
        $supplier = Supplier::factory()->create();

        $draft = \App\Models\Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $purchasing->id,
            'status' => 'draft',
        ]);

        $received = \App\Models\Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $purchasing->id,
            'status' => 'received',
        ]);

        $this->actingAs($purchasing)
            ->patch(route('purchasing.purchases.cancel', $draft))
            ->assertRedirect(route('purchasing.purchases.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('purchases', ['id' => $draft->id, 'status' => 'cancelled']);

        $this->actingAs($purchasing)
            ->patch(route('purchasing.purchases.cancel', $received))
            ->assertRedirect(route('purchasing.purchases.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('purchases', ['id' => $received->id, 'status' => 'received']);
    }

    public function test_non_purchasing_users_cannot_cancel_purchase_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $purchase = \App\Models\Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($admin)
            ->patch(route('purchasing.purchases.cancel', $purchase))
            ->assertForbidden();

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id, 'status' => 'draft']);
    }

}

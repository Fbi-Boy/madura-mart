<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PermissionOverride;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseReceivingTest extends TestCase
{
    use RefreshDatabase;


    public function test_gudang_requires_stock_manage_permission(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);

        PermissionOverride::create([
            'role' => 'gudang',
            'permission' => 'stock.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.index'))
            ->assertForbidden();
    }

    public function test_receiving_rejects_unknown_purchase_item_payload(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 10]);
        $purchase = Purchase::factory()->create([
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        $item = PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 12000,
            'subtotal' => 60000,
        ]);

        $unknownItemId = $item->id + 999;

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase), [
                'received' => [$unknownItemId => 5],
            ])
            ->assertSessionHasErrors("received.{$unknownItemId}");

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10,
        ]);
    }

    public function test_gudang_cannot_review_already_processed_purchase(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $purchase = Purchase::factory()->create([
            'status' => 'received',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.show', $purchase))
            ->assertNotFound();
    }

    public function test_gudang_can_see_draft_purchase_orders_for_receiving(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.index'))
            ->assertOk()
            ->assertViewIs('gudang.penerimaan.index');
    }


    public function test_gudang_can_review_submitted_purchase_before_receiving(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $supplier = Supplier::factory()->create(['name' => 'Supplier Review']);
        $product = Product::factory()->create(['name' => 'Produk Review', 'sku' => 'REV-001', 'stock' => 8]);

        $purchase = Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 12000,
            'subtotal' => 60000,
        ]);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.show', $purchase))
            ->assertOk()
            ->assertViewIs('gudang.penerimaan.show')
            ->assertSee('Supplier Review')
            ->assertSee('Produk Review')
            ->assertSee('REV-001')
            ->assertSee('Terima & Tambah Stok', false);
    }

    public function test_customer_cannot_view_purchase_receiving_review(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $purchase = Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.show', $purchase))
            ->assertForbidden();
    }

    public function test_gudang_receiving_changes_status_and_increases_stock(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $purchase = Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 12000,
            'subtotal' => 60000,
        ]);

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase))
            ->assertRedirect(route('gudang.penerimaan.index'));

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'received',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 15,
        ]);
    }


    public function test_gudang_can_record_received_and_damaged_quantities(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 10]);
        $purchase = Purchase::factory()->create([
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        $item = PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 12000,
            'subtotal' => 120000,
        ]);

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase), [
                'received' => [$item->id => 7],
                'damaged' => [$item->id => 2],
            ])
            ->assertRedirect(route('gudang.penerimaan.index'));

        $this->assertDatabaseHas('purchase_items', [
            'id' => $item->id,
            'received_quantity' => 7,
            'damaged_quantity' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 17,
        ]);
    }

    public function test_receiving_rejects_received_and_damaged_quantity_above_purchase_quantity(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 10]);
        $purchase = Purchase::factory()->create([
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        $item = PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 12000,
            'subtotal' => 120000,
        ]);

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase), [
                'received' => [$item->id => 8],
                'damaged' => [$item->id => 3],
            ])
            ->assertSessionHasErrors("received.{$item->id}");

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10,
        ]);
    }

    public function test_receiving_same_purchase_twice_does_not_double_stock(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 10]);
        $purchase = Purchase::factory()->create(['status' => 'received']);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 12000,
            'subtotal' => 60000,
        ]);

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase))
            ->assertRedirect(route('gudang.penerimaan.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10,
        ]);
    }

    public function test_customer_cannot_receive_purchase_orders(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $purchase = Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->post(route('gudang.penerimaan.receive', $purchase))
            ->assertForbidden();
    }

    public function test_gudang_can_filter_receiving_queue_by_invoice_and_date(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $supplier = Supplier::factory()->create(['name' => 'Supplier Filter']);

        $target = Purchase::factory()->create([
            'invoice' => 'PO-RECEIVE-TARGET',
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'submitted_at' => now()->subDay(),
            'purchase_date' => now()->subDay(),
        ]);

        Purchase::factory()->create([
            'invoice' => 'PO-RECEIVE-OTHER',
            'status' => 'draft',
            'submitted_at' => now()->subMonth(),
            'purchase_date' => now()->subMonth(),
        ]);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.index', [
                'q' => 'PO-RECEIVE-TARGET',
                'date_from' => now()->subDays(2)->toDateString(),
                'date_to' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertViewHas('purchases', fn ($purchases) =>
                $purchases->total() === 1
                && $purchases->first()->id === $target->id
            )
            ->assertSee('PO-RECEIVE-TARGET')
            ->assertDontSee('PO-RECEIVE-OTHER');
    }

    public function test_customer_cannot_access_receiving_queue_filters(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.index', ['q' => 'PO']))
            ->assertForbidden();
    }

}

<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseReceivingTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_can_see_draft_purchase_orders_for_receiving(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($user)
            ->get(route('gudang.penerimaan.index'))
            ->assertOk()
            ->assertViewIs('gudang.penerimaan.index');
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
}

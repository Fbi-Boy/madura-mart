<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_can_filter_purchase_list_and_open_detail(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $supplier = Supplier::factory()->create(['name' => 'Supplier Utama', 'is_active' => true]);
        $product = Product::factory()->create(['name' => 'Produk Utama', 'is_active' => true]);

        $target = Purchase::factory()->create([
            'invoice' => 'PO-TARGET-001',
            'supplier_id' => $supplier->id,
            'status' => 'draft',
            'submitted_at' => null,
            'purchase_date' => now()->subDay(),
        ]);
        PurchaseItem::create([
            'purchase_id' => $target->id,
            'product_id' => $product->id,
            'quantity' => 4,
            'unit_price' => 25000,
            'subtotal' => 100000,
        ]);

        Purchase::factory()->create([
            'invoice' => 'PO-OTHER-001',
            'status' => 'received',
            'purchase_date' => now()->subMonth(),
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.purchases.index', [
                'q' => 'PO-TARGET',
                'status' => 'draft',
                'date_from' => now()->subDays(2)->toDateString(),
                'date_to' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertViewIs('admin.purchases.index')
            ->assertViewHas('purchases', fn ($purchases) =>
                $purchases->total() === 1
                && $purchases->first()->id === $target->id
            )
            ->assertSee('PO-TARGET-001')
            ->assertDontSee('PO-OTHER-001');

        $this->actingAs($user)
            ->get(route('purchasing.purchases.show', $target))
            ->assertOk()
            ->assertViewIs('admin.purchases.show')
            ->assertViewHas('purchase', fn ($purchase) =>
                $purchase->id === $target->id
                && $purchase->items->count() === 1
                && $purchase->items->first()->product?->name === 'Produk Utama'
            )
            ->assertSee('Produk Utama')
            ->assertSee('Rp 100.000');
    }

    public function test_admin_can_open_purchase_detail(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $purchase = Purchase::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.purchases.show', $purchase))
            ->assertOk()
            ->assertViewIs('admin.purchases.show')
            ->assertSee($purchase->invoice);
    }
}

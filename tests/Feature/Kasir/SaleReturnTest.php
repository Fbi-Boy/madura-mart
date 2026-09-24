<?php

namespace Tests\Feature\Kasir;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleReturnTest extends TestCase
{
    use RefreshDatabase;

    public function test_return_restores_product_stock(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 7, 'price' => 12500, 'is_active' => true]);
        $sale = Sale::factory()->create(['user_id' => $kasir->id, 'status' => 'paid', 'total' => 37500]);
        $item = SaleItem::create(['sale_id' => $sale->id, 'product_id' => $product->id, 'quantity' => 3, 'unit_price' => 12500, 'subtotal' => 37500]);

        $this->actingAs($kasir)->post(route('kasir.retur.store'), [
            'return_number' => 'RET-0001', 'sale_id' => $sale->id, 'return_date' => '2026-09-24 22:00',
            'items' => [['sale_item_id' => $item->id, 'quantity' => 2]],
        ])->assertRedirect(route('kasir.retur'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 9]);
        $this->assertDatabaseHas('sale_return_items', ['sale_item_id' => $item->id, 'quantity' => 2, 'subtotal' => 25000]);
    }

    public function test_return_cannot_exceed_remaining_quantity(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 7, 'is_active' => true]);
        $sale = Sale::factory()->create(['user_id' => $kasir->id, 'status' => 'paid']);
        $item = SaleItem::create(['sale_id' => $sale->id, 'product_id' => $product->id, 'quantity' => 2, 'unit_price' => 10000, 'subtotal' => 20000]);

        $this->actingAs($kasir)->post(route('kasir.retur.store'), [
            'return_number' => 'RET-0002', 'sale_id' => $sale->id, 'return_date' => '2026-09-24 22:00',
            'items' => [['sale_item_id' => $item->id, 'quantity' => 3]],
        ])->assertStatus(422);

        $this->assertDatabaseMissing('sale_returns', ['return_number' => 'RET-0002']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 7]);
    }
}
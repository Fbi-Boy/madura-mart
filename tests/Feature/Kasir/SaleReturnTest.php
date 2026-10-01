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

    public function test_non_cashier_cannot_access_returns(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('kasir.retur'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('kasir.retur.create'))
            ->assertForbidden();
    }

    public function test_return_restores_product_stock(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 7, 'price' => 12500, 'is_active' => true]);
        $sale = Sale::factory()->create(['user_id' => $kasir->id, 'status' => 'paid', 'payment_method' => 'cash', 'total' => 37500]);
        $item = SaleItem::create(['sale_id' => $sale->id, 'product_id' => $product->id, 'quantity' => 3, 'unit_price' => 12500, 'subtotal' => 37500]);

        $this->actingAs($kasir)->post(route('kasir.retur.store'), [
            'return_number' => 'RET-0001', 'sale_id' => $sale->id, 'return_date' => now()->format('Y-m-d H:i:s'),
            'refund_method' => 'cash',
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
            'refund_method' => 'cash',
            'items' => [['sale_item_id' => $item->id, 'quantity' => 3]],
        ])->assertStatus(422);

        $this->assertDatabaseMissing('sale_returns', ['return_number' => 'RET-0002']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 7]);
    }

    public function test_return_cannot_use_item_from_another_sale(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);

        $saleA = Sale::factory()->create([
            'user_id' => $kasir->id,
            'status' => 'paid',
            'payment_method' => 'cash',
        ]);
        $saleB = Sale::factory()->create([
            'user_id' => $kasir->id,
            'status' => 'paid',
            'payment_method' => 'cash',
        ]);

        $itemFromSaleB = SaleItem::create([
            'sale_id' => $saleB->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.retur.store'), [
                'return_number' => 'RET-CROSS-SALE',
                'sale_id' => $saleA->id,
                'return_date' => now()->format('Y-m-d H:i:s'),
                'refund_method' => 'cash',
                'items' => [['sale_item_id' => $itemFromSaleB->id, 'quantity' => 1]],
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('sale_returns', ['return_number' => 'RET-CROSS-SALE']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
    }

    public function test_future_return_date_is_rejected(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $sale = Sale::factory()->create([
            'user_id' => $kasir->id,
            'status' => 'paid',
            'payment_method' => 'cash',
        ]);
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);
        $item = SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.retur.store'), [
                'return_number' => 'RET-FUTURE',
                'sale_id' => $sale->id,
                'return_date' => now()->addDay()->format('Y-m-d H:i:s'),
                'refund_method' => 'cash',
                'items' => [['sale_item_id' => $item->id, 'quantity' => 1]],
            ])
            ->assertSessionHasErrors('return_date');

        $this->assertDatabaseMissing('sale_returns', ['return_number' => 'RET-FUTURE']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
    }

    public function test_refund_method_must_match_original_sale_payment_method(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 5, 'price' => 50000]);

        $sale = Sale::factory()->create([
            'user_id' => $kasir->id,
            'status' => 'paid',
            'payment_method' => 'qris',
        ]);

        $saleItem = SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 50000,
            'subtotal' => 50000,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.retur.store'), [
                'return_number' => 'RET-METHOD-MISMATCH',
                'sale_id' => $sale->id,
                'return_date' => now()->format('Y-m-d H:i:s'),
                'refund_method' => 'cash',
                'items' => [['sale_item_id' => $saleItem->id, 'quantity' => 1]],
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('sale_returns', ['return_number' => 'RET-METHOD-MISMATCH']);
    }

}

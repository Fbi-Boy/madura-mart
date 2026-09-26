<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_outbound_shows_only_paid_sales_and_calculates_today_summary(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create([
            'name' => 'Beras Premium',
            'sku' => 'BRG-OUT-01',
            'stock' => 20,
        ]);

        $paidSale = Sale::factory()->create([
            'status' => 'paid',
            'sale_date' => now(),
        ]);

        SaleItem::factory()->create([
            'sale_id' => $paidSale->id,
            'product_id' => $product->id,
            'quantity' => 4,
        ]);

        $cancelledSale = Sale::factory()->create([
            'status' => 'cancelled',
            'sale_date' => now(),
        ]);

        SaleItem::factory()->create([
            'sale_id' => $cancelledSale->id,
            'product_id' => $product->id,
            'quantity' => 99,
        ]);

        $this->actingAs($user)
            ->get(route('gudang.barang-keluar.index'))
            ->assertOk()
            ->assertViewIs('gudang.barang-keluar.index')
            ->assertViewHas('todayUnits', 4)
            ->assertViewHas('todayTransactions', 1)
            ->assertViewHas('outboundItems', fn ($items) =>
                $items->total() === 1
                && $items->first()->quantity === 4
            )
            ->assertSee('BRG-OUT-01');
    }

    public function test_gudang_outbound_search_filters_by_product_or_invoice(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['name' => 'Minyak Goreng', 'sku' => 'MNY-001']);

        $sale = Sale::factory()->create(['status' => 'paid']);
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $otherProduct = Product::factory()->create(['name' => 'Gula', 'sku' => 'GLA-001']);
        $otherSale = Sale::factory()->create(['status' => 'paid']);
        SaleItem::factory()->create([
            'sale_id' => $otherSale->id,
            'product_id' => $otherProduct->id,
            'quantity' => 7,
        ]);

        $this->actingAs($user)
            ->get(route('gudang.barang-keluar.index', ['search' => 'MNY-001']))
            ->assertOk()
            ->assertViewHas('outboundItems', fn ($items) =>
                $items->total() === 1
                && $items->first()->product_id === $product->id
            );
    }

    public function test_non_gudang_cannot_access_outbound(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('gudang.barang-keluar.index'))
            ->assertForbidden();
    }
}

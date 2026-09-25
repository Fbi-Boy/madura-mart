<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GudangOutboundTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_can_view_paid_outbound_stock_history(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['name' => 'Beras Madura', 'sku' => 'BR-001', 'stock' => 10]);

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
            'quantity' => 9,
        ]);

        $this->actingAs($user)
            ->get(route('gudang.barang-keluar.index'))
            ->assertOk()
            ->assertViewIs('gudang.barang-keluar.index')
            ->assertViewHas('todayUnits', 4)
            ->assertViewHas('todayTransactions', 1)
            ->assertSee('Beras Madura')
            ->assertSee('BR-001');
    }

    public function test_non_gudang_cannot_access_outbound_stock_history(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($user)
            ->get(route('gudang.barang-keluar.index'))
            ->assertForbidden();
    }
}

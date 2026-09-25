<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_open_receipt_for_a_sale(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $shift = CashierShift::factory()->create([
            'user_id' => $cashier->id,
            'status' => 'open',
        ]);

        $product = Product::factory()->create([
            'name' => 'Beras Madura',
            'unit' => 'kg',
            'price' => 15000,
        ]);

        $sale = Sale::factory()->create([
            'user_id' => $cashier->id,
            'shift_id' => $shift->id,
            'status' => 'paid',
            'total' => 30000,
            'payment_method' => 'cash',
        ]);

        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 15000,
            'subtotal' => 30000,
        ]);

        $this->actingAs($cashier)
            ->get(route('kasir.transaksi-struk', $sale))
            ->assertOk()
            ->assertViewIs('kasir.struk.index')
            ->assertViewHas('sale', fn ($loadedSale) => $loadedSale->is($sale))
            ->assertSee('Beras Madura')
            ->assertSee('Cetak Struk');
    }

    public function test_non_cashier_cannot_open_cashier_receipt(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $sale = Sale::factory()->create();

        $this->actingAs($user)
            ->get(route('kasir.transaksi-struk', $sale))
            ->assertForbidden();
    }
}

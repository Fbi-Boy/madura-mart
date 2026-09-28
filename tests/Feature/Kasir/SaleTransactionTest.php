<?php

namespace Tests\Feature\Kasir;

use App\Models\Customer;
use App\Models\CashierShift;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_decreases_stock_and_creates_items(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create(['shift_number' => 'SHIFT-TEST-1', 'user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 100000, 'status' => 'open']);
        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 12500,
            'is_active' => true,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-0001',
                'sale_date' => now()->format('Y-m-d H:i:s'),
                'payment_method' => 'cash',
                'paid_amount' => 37500,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 3],
                ],
            ])
            ->assertRedirect(route('kasir.riwayat-transaksi'));

        $this->assertDatabaseHas('sales', [
            'invoice' => 'INV-0001',
            'user_id' => $kasir->id,
            'total' => 37500,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_price' => 12500,
            'subtotal' => 37500,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 7,
        ]);
    }

    public function test_sale_rejects_inactive_product(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create([
            'stock' => 10,
            'is_active' => false,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-0002',
                'sale_date' => '2026-09-24 22:00',
                'payment_method' => 'cash',
                'paid_amount' => 12500,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
            ])
            ->assertSessionHasErrors('items.0.product_id');
    }

    public function test_sale_rejects_quantity_above_stock(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create(['shift_number' => 'SHIFT-TEST-3', 'user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 100000, 'status' => 'open']);
        $product = Product::factory()->create([
            'stock' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-0003',
                'sale_date' => '2026-09-24 22:00',
                'payment_method' => 'cash',
                'paid_amount' => 37500,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 3],
                ],
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('sales', ['invoice' => 'INV-0003']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
    }

    public function test_sale_date_cannot_be_before_open_shift(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create([
            'shift_number' => 'SHIFT-DATE-1',
            'user_id' => $kasir->id,
            'opened_at' => '2026-09-24 10:00:00',
            'opening_cash' => 100000,
            'status' => 'open',
        ]);
        $product = Product::factory()->create(['stock' => 5, 'price' => 10000, 'is_active' => true]);

        $this->actingAs($kasir)->post(route('kasir.transaksi-baru.store'), [
            'invoice' => 'INV-DATE-1',
            'sale_date' => '2026-09-24 09:59:00',
            'payment_method' => 'cash',
            'paid_amount' => 10000,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertStatus(422);

        $this->assertDatabaseMissing('sales', ['invoice' => 'INV-DATE-1']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
    }

    public function test_sale_transaction_is_atomic_when_payment_is_insufficient(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create([
            'shift_number' => 'SHIFT-ATOMIC-1',
            'user_id' => $kasir->id,
            'opened_at' => now(),
            'opening_cash' => 100000,
            'status' => 'open',
        ]);
        $product = Product::factory()->create(['stock' => 5, 'price' => 20000, 'is_active' => true]);

        $this->actingAs($kasir)->post(route('kasir.transaksi-baru.store'), [
            'invoice' => 'INV-ATOMIC-1',
            'sale_date' => now()->format('Y-m-d H:i:s'),
            'payment_method' => 'cash',
            'paid_amount' => 10000,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertStatus(422);

        $this->assertDatabaseMissing('sales', ['invoice' => 'INV-ATOMIC-1']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
        $this->assertDatabaseMissing('stock_movements', ['type' => 'sale', 'quantity' => -1]);
    }

}

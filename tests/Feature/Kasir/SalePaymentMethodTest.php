<?php

namespace Tests\Feature\Kasir;

use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_complete_debit_sale(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create([
            'shift_number' => 'SHIFT-DEBIT-001',
            'user_id' => $kasir->id,
            'opened_at' => now()->subMinutes(10),
            'opening_cash' => 100000,
            'status' => 'open',
        ]);

        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-DEBIT-001',
                'sale_date' => now()->format('Y-m-d H:i:s'),
                'payment_method' => 'debit',
                'paid_amount' => 25000,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
            ])
            ->assertRedirect(route('kasir.riwayat-transaksi'));

        $this->assertDatabaseHas('sales', [
            'invoice' => 'INV-DEBIT-001',
            'payment_method' => 'debit',
            'total' => 25000,
            'paid_amount' => 25000,
            'change_amount' => 0,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 9,
        ]);
    }

    public function test_cashier_cannot_complete_cash_sale_with_insufficient_payment(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create([
            'shift_number' => 'SHIFT-PAYMENT-001',
            'user_id' => $kasir->id,
            'opened_at' => now()->subMinutes(10),
            'opening_cash' => 100000,
            'status' => 'open',
        ]);

        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-PAYMENT-001',
                'sale_date' => now()->format('Y-m-d H:i:s'),
                'payment_method' => 'cash',
                'paid_amount' => 10000,
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('sales', ['invoice' => 'INV-PAYMENT-001']);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10,
        ]);
    }

    public function test_sale_payment_method_is_limited_to_supported_methods(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create([
            'shift_number' => 'SHIFT-PAYMENT-002',
            'user_id' => $kasir->id,
            'opened_at' => now()->subMinutes(10),
            'opening_cash' => 100000,
            'status' => 'open',
        ]);

        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->actingAs($kasir)
            ->post(route('kasir.transaksi-baru.store'), [
                'invoice' => 'INV-PAYMENT-002',
                'sale_date' => now()->format('Y-m-d H:i:s'),
                'payment_method' => 'crypto',
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
            ])
            ->assertSessionHasErrors('payment_method');

        $this->assertDatabaseMissing('sales', ['invoice' => 'INV-PAYMENT-002']);
    }
}

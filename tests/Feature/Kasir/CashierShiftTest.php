<?php

namespace Tests\Feature\Kasir;

use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierShiftTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_open_and_close_shift(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->post(route('kasir.buka-shift.store'), [
            'shift_number' => 'SHIFT-0001',
            'opened_at' => '2026-09-24 22:00',
            'opening_cash' => 100000,
        ])->assertRedirect(route('kasir.tutup-shift'));

        $product = Product::factory()->create(['stock' => 10, 'price' => 12500, 'is_active' => true]);

        $this->actingAs($kasir)->post(route('kasir.transaksi-baru.store'), [
            'invoice' => 'INV-SHIFT-1',
            'sale_date' => '2026-09-24 22:10',
            'payment_method' => 'cash',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ])->assertRedirect();

        $this->actingAs($kasir)->post(route('kasir.tutup-shift.store'), [
            'closing_cash' => 125000,
            'closing_notes' => 'Tutup normal',
        ])->assertRedirect(route('kasir.riwayat-shift'));

        $this->assertDatabaseHas('cashier_shifts', [
            'shift_number' => 'SHIFT-0001',
            'status' => 'closed',
            'expected_cash' => 125000,
            'closing_cash' => 125000,
        ]);
    }

    public function test_cannot_open_second_shift_while_one_is_open(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        CashierShift::create(['shift_number'=>'SHIFT-OPEN','user_id'=>$kasir->id,'opened_at'=>now(),'opening_cash'=>0,'status'=>'open']);

        $this->actingAs($kasir)->post(route('kasir.buka-shift.store'), [
            'shift_number' => 'SHIFT-SECOND',
            'opened_at' => now()->format('Y-m-d H:i'),
            'opening_cash' => 50000,
        ])->assertSessionHasErrors('shift_number');

        $this->assertDatabaseMissing('cashier_shifts', ['shift_number' => 'SHIFT-SECOND']);
    }

    public function test_sales_page_requires_open_shift(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->get(route('kasir.transaksi-baru'))->assertNotFound();
    }
}
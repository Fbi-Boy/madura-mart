<?php

namespace Tests\Feature\Kasir;

use App\Models\User;
use App\Models\CashierShift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_kasir_can_access_new_sale_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);
        CashierShift::create(['shift_number' => 'SHIFT-ACCESS', 'user_id' => $kasir->id, 'opened_at' => now(), 'opening_cash' => 0, 'status' => 'open']);

        $this->actingAs($kasir)
            ->get(route('kasir.transaksi-baru'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('kasir.transaksi-baru'))
            ->assertForbidden();
    }
}
<?php

namespace Tests\Feature\Kasir;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_kasir_can_access_new_sale_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($kasir)
            ->get(route('kasir.transaksi-baru'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('kasir.transaksi-baru'))
            ->assertForbidden();
    }
}
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_can_view_stock_history_and_filter_by_product(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['name' => 'Beras Premium', 'sku' => 'BRG-001']);

        StockMovement::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'purchase_receipt',
            'quantity' => 20,
            'occurred_at' => now(),
        ]);

        StockMovement::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'sale',
            'quantity' => -3,
            'occurred_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('gudang.riwayat-stok.index', ['search' => 'BRG-001']))
            ->assertOk()
            ->assertViewIs('gudang.riwayat-stok.index')
            ->assertViewHas('movements', fn ($movements) => $movements->total() === 2)
            ->assertViewHas('summary', [
                'inbound' => 20,
                'outbound' => 3,
                'adjustment' => 0,
            ]);
    }

    public function test_non_gudang_roles_cannot_access_stock_history(): void
    {
        foreach (['admin', 'kasir', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('gudang.riwayat-stok.index'))
                ->assertForbidden();
        }
    }
}

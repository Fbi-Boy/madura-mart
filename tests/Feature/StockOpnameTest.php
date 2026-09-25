<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockOpnameTest extends TestCase
{
    use RefreshDatabase;

    public function test_gudang_can_open_stock_opname_page(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        Product::factory()->create(['is_active' => true, 'stock' => 12]);

        $this->actingAs($user)
            ->get('/gudang/stock-opname')
            ->assertOk()
            ->assertViewIs('gudang.stock-opname.index');
    }

    public function test_non_gudang_users_cannot_access_stock_opname(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/gudang/stock-opname')
            ->assertForbidden();
    }

    public function test_stock_opname_records_snapshot_and_updates_product_stock(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $matching = Product::factory()->create(['is_active' => true, 'stock' => 12]);
        $unchanged = Product::factory()->create(['is_active' => true, 'stock' => 8]);

        $response = $this->actingAs($user)->post('/gudang/stock-opname', [
            'notes' => 'Pengecekan rak A',
            'actual_stock' => [
                $matching->id => 15,
                $unchanged->id => 8,
            ],
        ]);

        $response
            ->assertRedirect(route('gudang.stock-opname.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('stock_opnames', [
            'user_id' => $user->id,
            'status' => 'completed',
            'notes' => 'Pengecekan rak A',
        ]);

        $opname = StockOpname::query()->latest('id')->firstOrFail();

        $this->assertDatabaseHas('stock_opname_items', [
            'stock_opname_id' => $opname->id,
            'product_id' => $matching->id,
            'system_stock' => 12,
            'actual_stock' => 15,
            'difference' => 3,
        ]);

        $this->assertDatabaseHas('stock_opname_items', [
            'stock_opname_id' => $opname->id,
            'product_id' => $unchanged->id,
            'system_stock' => 8,
            'actual_stock' => 8,
            'difference' => 0,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $matching->id,
            'stock' => 15,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $unchanged->id,
            'stock' => 8,
        ]);
    }

    public function test_stock_opname_ignores_inactive_products(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $inactive = Product::factory()->create(['is_active' => false, 'stock' => 20]);

        $this->actingAs($user)->post('/gudang/stock-opname', [
            'actual_stock' => [$inactive->id => 1],
        ])->assertRedirect(route('gudang.stock-opname.index'));

        $this->assertDatabaseCount('stock_opname_items', 0);
        $this->assertDatabaseHas('products', [
            'id' => $inactive->id,
            'stock' => 20,
        ]);
    }
}

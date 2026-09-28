<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InventoryEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_movement_service_updates_stock_and_records_balances(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 10]);

        $movement = StockMovementService::apply(
            $product,
            7,
            'purchase_receipt',
            $user,
            'purchase',
            123,
            'Penerimaan test',
        );

        $this->assertSame(17, $product->fresh()->stock);
        $this->assertSame(10, $movement->balance_before);
        $this->assertSame(17, $movement->balance_after);
        $this->assertSame(7, $movement->quantity);
        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'product_id' => $product->id,
            'type' => 'purchase_receipt',
            'quantity' => 7,
            'balance_before' => 10,
            'balance_after' => 17,
        ]);
    }

    public function test_outbound_movement_cannot_make_stock_negative(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $product = Product::factory()->create(['stock' => 3]);

        try {
            StockMovementService::apply(
                $product,
                -4,
                'sale',
                $user,
                'sale',
                1,
                'Penjualan test',
            );

            $this->fail('Expected a ValidationException.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('tidak boleh menjadi negatif', $exception->getMessage());
        }

        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseMissing('stock_movements', [
            'product_id' => $product->id,
            'type' => 'sale',
            'quantity' => -4,
        ]);
    }

    public function test_zero_quantity_is_rejected(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->expectException(ValidationException::class);

        StockMovementService::apply($product, 0, 'adjustment');
    }

    public function test_invalid_movement_type_is_rejected(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->expectException(ValidationException::class);

        StockMovementService::apply($product, 1, 'unknown');
    }

    public function test_sequential_stock_changes_keep_balances_consistent(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::factory()->create(['stock' => 20]);

        $first = StockMovementService::apply($product, -6, 'sale', $user);
        $second = StockMovementService::apply($product, 4, 'return', $user);
        $third = StockMovementService::apply($product, -3, 'sale', $user);

        $this->assertSame(20, $first->balance_before);
        $this->assertSame(14, $first->balance_after);
        $this->assertSame(14, $second->balance_before);
        $this->assertSame(18, $second->balance_after);
        $this->assertSame(18, $third->balance_before);
        $this->assertSame(15, $third->balance_after);
        $this->assertSame(15, $product->fresh()->stock);
        $this->assertSame(3, StockMovement::query()->where('product_id', $product->id)->count());
    }
}

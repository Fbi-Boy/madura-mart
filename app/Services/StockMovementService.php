<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StockMovementService
{
    public static function apply(
        Product $product,
        int $quantity,
        string $type,
        ?User $user = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
    ): StockMovement {
        if ($quantity === 0) {
            throw ValidationException::withMessages([
                'stock' => 'Perubahan stok harus lebih besar atau lebih kecil dari nol.',
            ]);
        }

        $allowedTypes = [
            'initial',
            'purchase_receipt',
            'sale',
            'return',
            'adjustment',
        ];

        if (! in_array($type, $allowedTypes, true)) {
            throw ValidationException::withMessages([
                'stock' => "Tipe pergerakan stok '{$type}' tidak valid.",
            ]);
        }

        $lockedProduct = Product::query()
            ->whereKey($product->id)
            ->lockForUpdate()
            ->firstOrFail();

        $balanceBefore = (int) $lockedProduct->stock;
        $balanceAfter = $balanceBefore + $quantity;

        if ($balanceAfter < 0) {
            throw ValidationException::withMessages([
                'stock' => "Stok produk {$lockedProduct->name} tidak boleh menjadi negatif.",
            ]);
        }

        $lockedProduct->update(['stock' => $balanceAfter]);

        return StockMovement::query()->create([
            'product_id' => $lockedProduct->id,
            'user_id' => $user?->id,
            'type' => $type,
            'quantity' => $quantity,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'occurred_at' => now(),
        ]);
    }

    /**
     * Legacy ledger-only helper for historical/manual ledger entries.
     * New stock mutations must use apply().
     */
    public static function record(
        Product $product,
        int $quantity,
        string $type,
        ?User $user = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
    ): StockMovement {
        return StockMovement::query()->create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'type' => $type,
            'quantity' => $quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'occurred_at' => now(),
        ]);
    }
}

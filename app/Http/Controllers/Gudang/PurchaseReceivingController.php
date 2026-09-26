<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseReceivingController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::query()
            ->with(['supplier:id,name', 'user:id,name'])
            ->withCount('items')
            ->where('status', 'draft')
            ->whereNotNull('submitted_at')
            ->latest('purchase_date')
            ->paginate(10);

        return view('gudang.penerimaan.index', compact('purchases'));
    }

    public function receive(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status !== 'draft' || $purchase->submitted_at === null) {
            return to_route('gudang.penerimaan.index')
                ->with('error', 'Purchase order sudah diproses dan tidak dapat diterima lagi.');
        }

        DB::transaction(function () use ($purchase): void {
            $lockedPurchase = Purchase::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($purchase->id);

            if ($lockedPurchase->status !== 'draft' || $lockedPurchase->submitted_at === null) {
                return;
            }

            foreach ($lockedPurchase->items as $item) {
                $product = $item->product()->lockForUpdate()->firstOrFail();
                $product->increment('stock', $item->quantity);
                StockMovementService::record($product, $item->quantity, 'purchase_receipt', auth()->user(), 'purchase', $lockedPurchase->id, "Penerimaan {$lockedPurchase->invoice}");
            }

            $lockedPurchase->update(['status' => 'received']);
        });

        return to_route('gudang.penerimaan.index')
            ->with('success', 'Purchase order berhasil diterima dan stok diperbarui.');
    }
}

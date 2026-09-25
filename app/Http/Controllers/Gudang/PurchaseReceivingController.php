<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
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
            ->latest('purchase_date')
            ->paginate(10);

        return view('gudang.penerimaan.index', compact('purchases'));
    }

    public function receive(Purchase $purchase): RedirectResponse
    {
        if ($purchase->status !== 'draft') {
            return to_route('gudang.penerimaan.index')
                ->with('error', 'Purchase order sudah diproses dan tidak dapat diterima lagi.');
        }

        DB::transaction(function () use ($purchase): void {
            $lockedPurchase = Purchase::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($purchase->id);

            if ($lockedPurchase->status !== 'draft') {
                return;
            }

            foreach ($lockedPurchase->items as $item) {
                $item->product()->lockForUpdate()->firstOrFail()->increment('stock', $item->quantity);
            }

            $lockedPurchase->update(['status' => 'received']);
        });

        return to_route('gudang.penerimaan.index')
            ->with('success', 'Purchase order berhasil diterima dan stok diperbarui.');
    }
}

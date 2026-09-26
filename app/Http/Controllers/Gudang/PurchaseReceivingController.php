<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public function show(Purchase $purchase): View
    {
        $purchase->load([
            'supplier:id,name,contact_person,phone',
            'user:id,name',
            'items.product:id,name,sku,unit',
        ]);

        return view('gudang.penerimaan.show', compact('purchase'));
    }

    public function receive(Request $request, Purchase $purchase): RedirectResponse
    {
        if ($purchase->status !== 'draft' || $purchase->submitted_at === null) {
            return to_route('gudang.penerimaan.index')
                ->with('error', 'Purchase order sudah diproses dan tidak dapat diterima lagi.');
        }

        DB::transaction(function () use ($request, $purchase): void {
            $lockedPurchase = Purchase::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($purchase->id);

            if ($lockedPurchase->status !== 'draft' || $lockedPurchase->submitted_at === null) {
                return;
            }

            $hasReceivingBreakdown = $request->has('received') || $request->has('damaged');
            $received = $request->input('received', []);
            $damaged = $request->input('damaged', []);

            foreach ($lockedPurchase->items as $item) {
                $receivedQuantity = $hasReceivingBreakdown
                    ? (int) ($received[$item->id] ?? 0)
                    : $item->quantity;
                $damagedQuantity = $hasReceivingBreakdown
                    ? (int) ($damaged[$item->id] ?? 0)
                    : 0;

                if (
                    $receivedQuantity < 0
                    || $damagedQuantity < 0
                    || ($receivedQuantity + $damagedQuantity) > $item->quantity
                ) {
                    throw ValidationException::withMessages([
                        "received.{$item->id}" => "Jumlah diterima dan rusak untuk item {$item->id} melebihi jumlah PO.",
                    ]);
                }

                $product = $item->product()->lockForUpdate()->firstOrFail();
                if ($receivedQuantity > 0) {
                    $product->increment('stock', $receivedQuantity);
                    StockMovementService::record(
                        $product,
                        $receivedQuantity,
                        'purchase_receipt',
                        auth()->user(),
                        'purchase',
                        $lockedPurchase->id,
                        "Penerimaan {$lockedPurchase->invoice}",
                    );
                }

                $item->update([
                    'received_quantity' => $receivedQuantity,
                    'damaged_quantity' => $damagedQuantity,
                ]);
            }

            $lockedPurchase->update(['status' => 'received']);
        });

        return to_route('gudang.penerimaan.index')
            ->with('success', 'Purchase order berhasil diterima dan stok diperbarui.');
    }
}

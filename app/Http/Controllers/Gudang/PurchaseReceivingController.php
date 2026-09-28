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
    public function index(Request $request): View
    {
        $purchases = Purchase::query()
            ->with(['supplier:id,name', 'user:id,name'])
            ->withCount('items')
            ->where('status', 'draft')
            ->whereNotNull('submitted_at')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->input('q'));

                $query->where(function ($query) use ($term) {
                    $query->where('invoice', 'like', "%{$term}%")
                        ->orWhereHas('supplier', fn ($supplier) => $supplier->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('purchase_date', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('purchase_date', '<=', $request->date('date_to')))
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('gudang.penerimaan.index', compact('purchases'));
    }

    public function show(Purchase $purchase): View
    {
        abort_unless(
            $purchase->status === 'draft' && $purchase->submitted_at !== null,
            404,
        );

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

        $validated = $request->validate([
            'received' => ['sometimes', 'array'],
            'received.*' => ['integer', 'min:0'],
            'damaged' => ['sometimes', 'array'],
            'damaged.*' => ['integer', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $purchase): void {
            $lockedPurchase = Purchase::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($purchase->id);

            if ($lockedPurchase->status !== 'draft' || $lockedPurchase->submitted_at === null) {
                return;
            }

            $hasReceivingBreakdown = array_key_exists('received', $validated) || array_key_exists('damaged', $validated);
            $received = $validated['received'] ?? [];
            $damaged = $validated['damaged'] ?? [];
            $itemIds = $lockedPurchase->items->pluck('id')->map(fn ($id) => (string) $id)->all();

            foreach (array_keys($received) as $itemId) {
                if (! in_array((string) $itemId, $itemIds, true)) {
                    throw ValidationException::withMessages([
                        "received.{$itemId}" => 'Item penerimaan tidak termasuk dalam purchase order.',
                    ]);
                }
            }

            foreach (array_keys($damaged) as $itemId) {
                if (! in_array((string) $itemId, $itemIds, true)) {
                    throw ValidationException::withMessages([
                        "damaged.{$itemId}" => 'Item penerimaan tidak termasuk dalam purchase order.',
                    ]);
                }
            }

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
                    StockMovementService::apply(
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

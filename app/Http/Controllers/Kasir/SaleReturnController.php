<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SaleReturnController extends Controller
{
    public function index(): View
    {
        $returns = SaleReturn::with(['sale', 'user'])->latest('return_date')->paginate(10);
        return view('kasir.retur.index', compact('returns'));
    }

    public function create(): View
    {
        $sales = Sale::query()->where('status', 'paid')->latest('sale_date')->limit(100)->get(['id', 'invoice', 'sale_date', 'total']);
        return view('kasir.retur.create', compact('sales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'return_number' => ['required', 'string', 'max:50', 'unique:sale_returns,return_number'],
            'sale_id' => ['required', Rule::exists('sales', 'id')->where('status', 'paid')],
            'return_date' => ['required', 'date'],
            'reason' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'distinct', 'exists:sale_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $sale = Sale::query()->whereKey($validated['sale_id'])->where('status', 'paid')->lockForUpdate()->firstOrFail();

            $return = SaleReturn::create([
                'return_number' => $validated['return_number'],
                'sale_id' => $sale->id,
                'user_id' => auth()->id(),
                'return_date' => $validated['return_date'],
                'total' => 0,
                'reason' => $validated['reason'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $input) {
                $saleItem = SaleItem::query()->whereKey($input['sale_item_id'])->lockForUpdate()->firstOrFail();

                if ($saleItem->sale_id !== $sale->id) {
                    abort(422, 'Item retur tidak berasal dari transaksi yang dipilih.');
                }

                $returned = (int) SaleReturnItem::query()
                    ->where('sale_item_id', $saleItem->id)
                    ->sum('quantity');

                $remaining = $saleItem->quantity - $returned;

                if ($input['quantity'] > $remaining) {
                    abort(422, 'Jumlah retur melebihi sisa item yang dapat diretur.');
                }

                $subtotal = (float) $saleItem->unit_price * $input['quantity'];

                $return->items()->create([
                    'sale_item_id' => $saleItem->id,
                    'quantity' => $input['quantity'],
                    'unit_price' => $saleItem->unit_price,
                    'subtotal' => $subtotal,
                ]);

                $saleItem->product()->lockForUpdate()->increment('stock', $input['quantity']);
                $total += $subtotal;
            }

            $return->update(['total' => $total]);
        });

        return redirect()->route('kasir.retur')->with('success', 'Retur penjualan berhasil disimpan.');
    }
}
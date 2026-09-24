<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::with(['supplier','user'])->latest('purchase_date')->paginate(10);
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        return view('admin.purchases.create', [
            'suppliers' => Supplier::query()->where('is_active', true)->orderBy('name')->get(),
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice' => ['required','string','max:50','unique:purchases,invoice'],
            'supplier_id' => ['required','exists:suppliers,id'],
            'purchase_date' => ['required','date'],
            'notes' => ['nullable','string'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1'],
            'items.*.unit_price' => ['required','numeric','min:0'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $purchase = Purchase::create([
                'invoice' => $data['invoice'],
                'supplier_id' => $data['supplier_id'],
                'user_id' => $request->user()->id,
                'purchase_date' => $data['purchase_date'],
                'status' => 'received',
                'notes' => $data['notes'] ?? null,
                'total' => 0,
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $item['unit_price'];

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                $product->increment('stock', $item['quantity']);
                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);
        });

        return to_route('admin.purchases.index')->with('success', 'Pembelian berhasil dicatat dan stok diperbarui.');
    }
}
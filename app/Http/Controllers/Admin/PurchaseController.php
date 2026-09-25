<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
            'purchaseRoutePrefix' => auth()->user()->role === 'purchasing' ? 'purchasing.purchases' : 'admin.purchases',
            'suppliers' => Supplier::query()->where('is_active', true)->orderBy('name')->get(),
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice' => ['required','string','max:50','unique:purchases,invoice'],
            'supplier_id' => ['required', Rule::exists('suppliers', 'id')->where(fn ($query) => $query->where('is_active', true))],
            'purchase_date' => ['required','date'],
            'notes' => ['nullable','string'],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','distinct', Rule::exists('products', 'id')->where(fn ($query) => $query->where('is_active', true))],
            'items.*.quantity' => ['required','integer','min:1'],
            'items.*.unit_price' => ['required','numeric','min:0'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $purchase = Purchase::create([
                'invoice' => $data['invoice'],
                'supplier_id' => $data['supplier_id'],
                'user_id' => $request->user()->id,
                'purchase_date' => $data['purchase_date'],
                'status' => $request->user()->role === 'purchasing' ? 'draft' : 'received',
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

                if ($request->user()->role !== 'purchasing') {
                    $product->increment('stock', $item['quantity']);
                }
                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);
        });

        $route = $request->user()->role === 'purchasing' ? 'purchasing.purchases.index' : 'admin.purchases.index';
        $message = $request->user()->role === 'purchasing'
            ? 'Purchase order berhasil dibuat sebagai draft dan belum mengubah stok.'
            : 'Pembelian berhasil dicatat dan stok diperbarui.';

        return to_route($route)->with('success', $message);
    }
}
<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\StockMovementService;
use App\Models\CashierShift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['customer', 'user'])
            ->latest('sale_date')
            ->paginate(10);

        return view('kasir.riwayat-transaksi.index', compact('sales'));
    }

    public function create(): View
    {
        $shift = CashierShift::where('user_id', auth()->id())->where('status', 'open')->firstOrFail();

        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'stock']);

        $customers = Customer::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('kasir.transaksi-baru.index', compact('products', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice' => ['required', 'string', 'max:50', 'unique:sales,invoice'],
            'customer_id' => ['nullable', Rule::exists('customers', 'id')->where('is_active', true)],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris'])],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', Rule::exists('products', 'id')->where('is_active', true)],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $sale = Sale::create([
                'invoice' => $validated['invoice'],
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'shift_id' => CashierShift::where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->firstOrFail()->id,
                'sale_date' => $validated['sale_date'],
                'total' => 0,
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'notes' => $validated['notes'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $item['quantity']) {
                    abort(422, "Stok produk {$product->name} tidak mencukupi.");
                }

                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $item['quantity'];

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $item['quantity']);
                StockMovementService::record($product, -$item['quantity'], 'sale', auth()->user(), 'sale', $sale->id, "Penjualan {$sale->invoice}");
                $total += $subtotal;
            }

            $sale->update(['total' => $total]);
        });

        return redirect()
            ->route('kasir.riwayat-transaksi')
            ->with('success', 'Transaksi penjualan berhasil disimpan.');
    }
}
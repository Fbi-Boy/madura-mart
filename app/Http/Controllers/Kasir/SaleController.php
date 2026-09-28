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
            ->where('user_id', auth()->id())
            ->latest('sale_date')
            ->paginate(10);

        return view('kasir.riwayat-transaksi.index', compact('sales'));
    }

    public function receipt(Sale $sale): View
    {
        abort_unless($sale->user_id === auth()->id(), 403);

        $sale->load(['customer:id,name', 'user:id,name', 'items.product:id,name,unit']);

        return view('kasir.struk.index', compact('sale'));
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
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris', 'debit'])],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,cash'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', Rule::exists('products', 'id')->where('is_active', true)],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $shift = CashierShift::query()
                ->where('user_id', auth()->id())
                ->where('status', 'open')
                ->lockForUpdate()
                ->firstOrFail();

            $saleDate = now()->parse($validated['sale_date']);
            if ($saleDate->lt($shift->opened_at)) {
                abort(422, 'Tanggal transaksi tidak boleh sebelum shift dibuka.');
            }

            $sale = Sale::create([
                'invoice' => $validated['invoice'],
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'shift_id' => $shift->id,
                'sale_date' => $validated['sale_date'],
                'total' => 0,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => 0,
                'change_amount' => 0,
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

                StockMovementService::apply($product, -$item['quantity'], 'sale', auth()->user(), 'sale', $sale->id, "Penjualan {$sale->invoice}");
                $total += $subtotal;
            }

            $paidAmount = $validated['payment_method'] === 'cash'
                ? (float) ($validated['paid_amount'] ?? 0)
                : $total;

            if ($paidAmount < $total) {
                abort(422, 'Nominal pembayaran tidak mencukupi.');
            }

            $sale->update([
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => max(0, $paidAmount - $total),
            ]);
        });

        return redirect()
            ->route('kasir.riwayat-transaksi')
            ->with('success', 'Transaksi penjualan berhasil disimpan.');
    }
}
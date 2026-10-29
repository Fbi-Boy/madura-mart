<?php

namespace App\Http\Controllers\Kasir;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function create(): View
    {
       $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        $productData = $products->map(fn ($p) => [
            'id' => $p->id,
            'sku' => $p->sku ?? '',
            'name' => $p->name ?? 'Tanpa Nama',
            'price' => (float) ($p->price ?? 0),
            'stock' => (int) ($p->stock ?? 0),
            'unit' => $p->unit ?? '',
        ])->values()->toArray();

        $shiftAktif = CashierShift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->latest('opened_at')
            ->first();

        return view('kasir.transaksi-baru.index', compact(
            'customers',
            'products',
            'productData',
            'shiftAktif'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'invoice' => ['required', 'string', 'max:50', 'unique:sales,invoice'],
                'customer_id' => ['nullable', 'exists:customers,id'],
                'sale_date' => ['required', 'date'],
                'payment_method' => ['required', 'in:cash,transfer,qris,debit'],
                'paid_amount' => ['nullable', 'numeric', 'min:0'],
                'diskon' => ['nullable', 'numeric', 'min:0'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
            ]);

            $sale = DB::transaction(function () use ($validated) {
                $shift = CashierShift::where('user_id', Auth::id())
                    ->whereNull('closed_at')
                    ->latest('opened_at')
                    ->lockForUpdate()
                    ->first();

                if (!$shift) {
                    abort(422, 'Anda belum membuka shift. Silakan buka shift terlebih dahulu.');
                }

                $subtotal = 0;
                $itemsData = [];

                foreach ($validated['items'] as $input) {
                    $product = Product::whereKey($input['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    $productPrice = (float) $product->price;
                    $currentStock = (int) $product->stock;

                    if ($currentStock < $input['quantity']) {
                        abort(
                            422,
                            "Stok produk {$product->name} tidak mencukupi. Tersisa: {$currentStock} {$product->unit}."
                        );
                    }

                    $lineSubtotal = $productPrice * $input['quantity'];
                    $subtotal += $lineSubtotal;

                    $itemsData[] = [
                        'product' => $product,
                        'quantity' => $input['quantity'],
                        'unit_price' => $productPrice,
                        'subtotal' => $lineSubtotal,
                    ];
                }

                $diskon = (float) ($validated['diskon'] ?? 0);
                $diskon = min(max(0, $diskon), $subtotal);

                $afterDiskon = $subtotal - $diskon;
                $tax = round($afterDiskon * 0.11);
                $total = $afterDiskon + $tax;

                $paid = $validated['payment_method'] === 'cash'
                    ? (float) ($validated['paid_amount'] ?? 0)
                    : $total;

                if ($validated['payment_method'] === 'cash' && $paid < $total) {
                    abort(422, 'Nominal pembayaran kurang dari total tagihan.');
                }

                $sale = Sale::create([
                    'invoice' => $validated['invoice'],
                    'customer_id' => $validated['customer_id'] ?: null,
                    'user_id' => Auth::id(),
                    'shift_id' => $shift->id,
                    'sale_date' => $validated['sale_date'],
                    'subtotal' => $subtotal,
                    'diskon' => $diskon,
                    'tax' => $tax,
                    'total' => $total,
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $paid,
                    'change_amount' => max(0, $paid - $total),
                    'status' => 'paid',
                ]);

                foreach ($itemsData as $data) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $data['product']->id,
                        'quantity' => $data['quantity'],
                        'unit_price' => $data['unit_price'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['product']->decrement('stock', $data['quantity']);

                    try {
                        $user = Auth::user();

                        if ($user) {
                            StockMovementService::apply(
                                $data['product'],
                                -$data['quantity'],
                                'sale',
                                $user,
                                'sale',
                                $sale->id,
                                "Penjualan {$sale->invoice}"
                            );
                        }
                    } catch (\Throwable $e) {
                        report($e);
                    }
                }

                return $sale;
            });

            $sale->load(['items.product', 'user']);

            $user = Auth::user();

            event(new OrderPlaced(
                (int) $sale->id,
                (float) $sale->total,
                (string) ($sale->user->name ?? $user?->name ?? '-'),
                (int) $sale->user_id,
                (string) $sale->invoice
            ));

            return response()->json([
                'success' => true,
                'sale' => [
                    'invoice' => $sale->invoice,
                    'kasir' => $sale->user->name ?? '-',
                    'date' => $sale->sale_date->format('d/m/Y H:i'),
                    'items' => $sale->items->map(fn ($it) => [
                        'name' => $it->product->name ?? 'Tanpa Nama',
                        'qty' => $it->quantity,
                        'price' => (float) $it->unit_price,
                    ])->toArray(),
                    'subtotal' => (float) $sale->subtotal,
                    'diskon' => (float) $sale->diskon,
                    'pajak' => (float) $sale->tax,
                    'total' => (float) $sale->total,
                    'paid' => (float) $sale->paid_amount,
                    'change' => (float) $sale->change_amount,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile()),
            ], 500);
        }
    }

    public function index(): View
    {
        $sales = Sale::with(['items.product', 'customer', 'user', 'shift'])
            ->where('user_id', Auth::id())
            ->latest('sale_date')
            ->paginate(20);

        return view('kasir.riwayat-transaksi.index', compact('sales'));
    }

    public function receipt(Sale $sale): View
    {
        $user = Auth::user();

        abort_unless($user, 401);

        $isAdmin = in_array($user->role, ['admin', 'super-admin'], true);

        abort_unless(
            (int) $sale->user_id === (int) $user->id || $isAdmin,
            403
        );

        $sale->load(['items.product', 'customer', 'user', 'shift']);

        return view('kasir.transaksi-baru.struk', compact('sale'));
    }
}

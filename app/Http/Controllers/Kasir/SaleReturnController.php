<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SaleReturn::with([
            'sale',
            'user',
            'items.saleItem.product',
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                    ->orWhereHas('sale', function ($saleQuery) use ($search) {
                        $saleQuery->where('invoice', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate(
                'return_date',
                '>=',
                $request->input('start_date')
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'return_date',
                '<=',
                $request->input('end_date')
            );
        }

        $returns = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.monitoring.retur-penjualan.index',
            compact('returns')
        );
    }

    public function create(Sale $sale)
    {
        $sale->load([
            'customer',
            'items.product',
            'return.items',
        ]);

        if ($sale->status !== 'paid') {
            return back()->with(
                'error',
                'Hanya transaksi yang sudah dibayar yang dapat diretur.'
            );
        }

        return view(
            'admin.monitoring.retur-penjualan.create',
            compact('sale')
        );
    }

    public function store(Request $request, Sale $sale): RedirectResponse
    {
        $validated = $request->validate([
            'return_date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'refund_method' => [
                'required',
                'string',
                'in:cash,transfer,qris',
            ],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => [
                'required',
                'integer',
                'exists:sale_items,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $return = DB::transaction(function () use ($validated, $sale) {
            $sale->load('items.product');

            if ($sale->status !== 'paid') {
                throw ValidationException::withMessages([
                    'sale' => 'Transaksi belum dibayar atau tidak dapat diretur.',
                ]);
            }

            $returnNumber = $this->generateReturnNumber();

            $saleReturn = SaleReturn::create([
                'return_number' => $returnNumber,
                'sale_id' => $sale->getKey(),
                'user_id' => Auth::id(),
                'return_date' => $validated['return_date'],
                'total' => 0,
                'refund_method' => $validated['refund_method'],
                'reason' => $validated['reason'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $saleItem = $sale->items->firstWhere(
                    'id',
                    (int) $item['sale_item_id']
                );

                if (!$saleItem) {
                    throw ValidationException::withMessages([
                        'items' => 'Item penjualan tidak ditemukan pada transaksi tersebut.',
                    ]);
                }

                $alreadyReturned = SaleReturnItem::query()
                    ->where('sale_item_id', $saleItem->getKey())
                    ->sum('quantity');

                $availableQuantity = $saleItem->quantity - $alreadyReturned;

                $quantity = (int) $item['quantity'];

                if ($quantity > $availableQuantity) {
                    throw ValidationException::withMessages([
                        'items' => "Jumlah retur untuk {$saleItem->product->name} melebihi jumlah yang dapat diretur.",
                    ]);
                }

                $unitPrice = $saleItem->unit_price;
                $subtotal = $quantity * $unitPrice;

                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->getKey(),
                    'sale_item_id' => $saleItem->getKey(),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                StockMovementService::apply(
                    product: $saleItem->product,
                    quantity: $quantity,
                    type: 'return',
                    user: Auth::user(),
                    referenceType: SaleReturn::class,
                    referenceId: (int) $saleReturn->getKey(),
                    notes: "Retur penjualan {$sale->invoice}"
                );

                $total += $subtotal;
            }

            if ($total <= 0) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal satu item harus diretur.',
                ]);
            }

            $saleReturn->update([
                'total' => $total,
            ]);

            return $saleReturn;
        });

        return redirect()
            ->route('admin.sale-returns.index')
            ->with(
                'success',
                "Retur {$return->return_number} berhasil dibuat."
            );
    }

    public function show(SaleReturn $saleReturn)
    {
        $saleReturn->load([
            'sale.customer',
            'user',
            'items.saleItem.product',
        ]);

        return view(
            'admin.monitoring.retur-penjualan.show',
            compact('saleReturn')
        );
    }

    private function generateReturnNumber(): string
    {
        $prefix = 'RET-' . now()->format('Ymd');

        $lastReturn = SaleReturn::query()
            ->where('return_number', 'like', $prefix . '-%')
            ->latest('id')
            ->first();

        $number = $lastReturn
            ? ((int) substr($lastReturn->return_number, -4)) + 1
            : 1;

        return $prefix . '-' . str_pad(
            $number,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}

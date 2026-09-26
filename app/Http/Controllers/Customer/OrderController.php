<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $customer = Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();

        $orders = $customer
            ? Order::query()
                ->withCount('items')
                ->with('courier:id,name')
                ->where('customer_id', $customer->id)
                ->latest('order_date')
                ->paginate(10)
                ->withQueryString()
            : Order::query()
                ->whereRaw('1 = 0')
                ->paginate(10);

        return view('customer.orders.index', compact('customer', 'orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $customer = $this->customer($request);

        abort_unless($customer && $order->customer_id === $customer->id, 404);

        $order->load([
            'items.product:id,name,sku,unit',
            'courier:id,name',
        ]);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $customer = $this->customer($request);

        abort_unless($customer && $order->customer_id === $customer->id, 404);

        DB::transaction(function () use ($order, $customer): void {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->customer_id !== $customer->id) {
                abort(404);
            }

            if ($lockedOrder->status !== 'pending') {
                abort(422, 'Pesanan yang sudah diproses tidak dapat dibatalkan.');
            }

            if ($lockedOrder->payment_status === 'paid') {
                abort(422, 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
            }

            $lockedOrder->load('items');

            foreach ($lockedOrder->items as $item) {
                $product = Product::query()
                    ->whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $product->increment('stock', $item->quantity);

                StockMovementService::record(
                    $product,
                    (int) $item->quantity,
                    'return',
                    auth()->user(),
                    'order_cancellation',
                    $lockedOrder->id,
                    'Stok dikembalikan karena pesanan customer dibatalkan',
                );
            }

            $lockedOrder->update(['status' => 'cancelled']);
        });

        return to_route('customer.orders.show', $order)
            ->with('status', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
    }

    private function customer(Request $request): ?Customer
    {
        return Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();
    }
}

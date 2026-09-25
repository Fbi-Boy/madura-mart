<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View
    {
        $cart = $request->session()->get('customer_cart', []);

        if ($cart === []) {
            return view('customer.checkout.index', [
                'items' => collect(),
                'total' => 0,
                'customer' => $this->customer($request),
            ]);
        }

        $items = $this->cartItems($cart);
        $total = $items->sum('subtotal');

        return view('customer.checkout.index', [
            'items' => $items,
            'total' => $total,
            'customer' => $this->customer($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = $this->customer($request);
        abort_unless($customer, 403);

        $cart = $request->session()->get('customer_cart', []);
        abort_if($cart === [], 422, 'Keranjang masih kosong.');

        $order = DB::transaction(function () use ($cart, $customer) {
            $items = collect($cart)->mapWithKeys(fn ($quantity, $productId) => [
                (int) $productId => (int) $quantity,
            ])->filter(fn ($quantity) => $quantity > 0);

            $total = 0;
            $lockedProducts = [];

            foreach ($items as $productId => $quantity) {
                $product = Product::query()
                    ->whereKey($productId)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                abort_unless($product && $product->stock >= $quantity, 422, 'Stok produk berubah. Silakan periksa kembali keranjang.');

                $subtotal = $quantity * (float) $product->price;
                $total += $subtotal;
                $lockedProducts[$productId] = [$product, $quantity, $subtotal];
            }

            do {
                $orderNumber = 'MM-'.now()->format('YmdHis').'-'.Str::upper(Str::random(5));
            } while (Order::query()->where('order_number', $orderNumber)->exists());

            $order = Order::query()->create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'order_date' => now(),
                'total' => $total,
                'status' => 'pending',
                'delivery_address' => trim(implode(', ', array_filter([$customer->address, $customer->city]))),
            ]);

            foreach ($lockedProducts as [$product, $quantity, $subtotal]) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        $request->session()->forget('customer_cart');

        return redirect()
            ->route('customer.checkout.success', $order)
            ->with('status', 'Pesanan berhasil dibuat.');
    }

    public function success(Order $order): View
    {
        $order->load(['items.product:id,name,unit', 'customer:id,name,email,address,city']);

        abort_unless($order->customer_id === optional($this->customer(request()))->id, 403);

        return view('customer.checkout.success', compact('order'));
    }

    private function customer(Request $request): ?Customer
    {
        return Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();
    }

    private function cartItems(array $cart)
    {
        $products = \App\Models\Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get(['id', 'name', 'sku', 'price', 'stock', 'unit']);

        return $products->map(function ($product) use ($cart) {
            $quantity = min((int) ($cart[$product->id] ?? 0), $product->stock);

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $quantity * (float) $product->price,
            ];
        })->filter(fn (array $item) => $item['quantity'] > 0)->values();
    }
}

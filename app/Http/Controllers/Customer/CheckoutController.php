<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\SystemSetting;
use App\Services\StockMovementService;
use App\Services\OrderStateMachine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View
    {
        $customer = $this->customer($request);
        $cart = $request->session()->get('customer_cart', []);
        $addresses = $customer->addresses()->orderByDesc('is_default')->latest('id')->get();

        if ($cart === []) {
            return view('customer.checkout.index', ['items' => collect(), 'total' => 0, 'customer' => $customer, 'addresses' => $addresses]);
        }

        $items = $this->cartItems($cart);
        $total = $items->sum('subtotal');
        $minimumOrder = (float) SystemSetting::valueFor('minimum_order', 0);
        $paymentMethods = $this->paymentMethods();

        return view('customer.checkout.index', compact('items', 'total', 'customer', 'addresses', 'minimumOrder', 'paymentMethods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $availablePaymentMethods = $this->paymentMethods();
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(array_keys($availablePaymentMethods))],
            'address_id' => ['required', 'integer'],
        ]);

        $customer = $this->customer($request);
        $address = $customer->addresses()->whereKey($validated['address_id'])->first();
        abort_unless($address, 422, 'Alamat pengiriman tidak valid.');

        $cart = $request->session()->get('customer_cart', []);
        abort_if($cart === [], 422, 'Keranjang masih kosong.');

        $order = DB::transaction(function () use ($cart, $customer, $address, $request, $validated) {
            $items = collect($cart)->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => (int) $quantity])->filter(fn ($quantity) => $quantity > 0);
            $total = 0;
            $lockedProducts = [];

            foreach ($items as $productId => $quantity) {
                $product = Product::query()->whereKey($productId)->where('is_active', true)->lockForUpdate()->first();
                abort_unless($product && $product->stock >= $quantity, 422, 'Stok produk berubah. Silakan periksa kembali keranjang.');
                $subtotal = $quantity * (float) $product->price;
                $total += $subtotal;
                $lockedProducts[$productId] = [$product, $quantity, $subtotal];
            }

            $minimumOrder = (float) SystemSetting::valueFor('minimum_order', 0);
            abort_if($total < $minimumOrder, 422, 'Minimum belanja Rp '.number_format($minimumOrder, 0, ',', '.').' belum terpenuhi.');

            do {
                $orderNumber = 'MM-'.now()->format('YmdHis').'-'.Str::upper(Str::random(5));
            } while (Order::query()->where('order_number', $orderNumber)->exists());

            $order = Order::query()->create([
                'order_number' => $orderNumber, 'customer_id' => $customer->id, 'order_date' => now(),
                'total' => $total, 'payment_method' => $validated['payment_method'], 'payment_status' => 'pending',
                'status' => 'pending', 'delivery_address' => $address->fullAddress(),
            ]);

            OrderStateMachine::recordInitial($order, auth()->user(), 'Pesanan dibuat melalui checkout customer.');

            foreach ($lockedProducts as [$product, $quantity, $subtotal]) {
                $order->items()->create(['product_id' => $product->id, 'quantity' => $quantity, 'unit_price' => $product->price, 'subtotal' => $subtotal]);
                StockMovementService::apply($product, -$quantity, 'sale', auth()->user(), 'order', $order->id, "Penjualan online {$order->order_number}");
            }

            return $order;
        });

        $request->session()->forget('customer_cart');
        return redirect()->route('customer.checkout.success', $order)->with('status', 'Pesanan berhasil dibuat.');
    }

    public function success(Order $order): View
    {
        $order->load(['items.product:id,name,unit', 'customer:id,name,email,address,city']);
        abort_unless($order->customer_id === optional($this->customer(request()))->id, 403);
        return view('customer.checkout.success', compact('order'));
    }

    private function paymentMethods(): array
    {
        $configured = (string) SystemSetting::valueFor('payment_methods', 'QRIS, Transfer Bank');
        $mapping = ['qris' => ['QRIS', 'qris'], 'bank_transfer' => ['Transfer Bank', 'bank_transfer']];
        $methods = [];
        foreach (array_map('trim', explode(',', $configured)) as $label) {
            foreach ($mapping as $method => [$configuredLabel]) {
                if (strcasecmp($label, $configuredLabel) === 0) {
                    $methods[$method] = $configuredLabel;
                    break;
                }
            }
        }
        return $methods ?: ['qris' => 'QRIS', 'bank_transfer' => 'Transfer Bank'];
    }

    private function customer(Request $request): Customer
    {
        $customer = Customer::query()->where('email', $request->user()->email)->where('is_active', true)->firstOrFail();

        if ($customer->addresses()->doesntExist() && $customer->address) {
            $customer->addresses()->create([
                'label' => 'Alamat Utama', 'recipient_name' => $customer->name, 'phone' => $customer->phone,
                'address' => $customer->address, 'city' => $customer->city, 'is_default' => true,
            ]);
        }

        return $customer;
    }

    private function cartItems(array $cart)
    {
        $products = Product::query()->whereIn('id', array_keys($cart))->where('is_active', true)->where('stock', '>', 0)->get(['id', 'name', 'sku', 'price', 'stock', 'unit']);
        return $products->map(function ($product) use ($cart) {
            $quantity = min((int) ($cart[$product->id] ?? 0), $product->stock);
            return ['product' => $product, 'quantity' => $quantity, 'subtotal' => $quantity * (float) $product->price];
        })->filter(fn (array $item) => $item['quantity'] > 0)->values();
    }
}

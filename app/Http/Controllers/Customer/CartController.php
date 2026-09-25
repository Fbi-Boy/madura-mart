<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('customer_cart', []);

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get(['id', 'name', 'sku', 'price', 'stock', 'unit']);

        $items = $products->map(function (Product $product) use ($cart) {
            $quantity = min((int) ($cart[$product->id] ?? 0), $product->stock);

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $quantity * (float) $product->price,
            ];
        })->filter(fn (array $item) => $item['quantity'] > 0)->values();

        $total = $items->sum('subtotal');

        return view('customer.cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->stock > 0, 404);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('customer_cart', []);
        $current = (int) ($cart[$product->id] ?? 0);
        $cart[$product->id] = min($current + $data['quantity'], $product->stock);

        $request->session()->put('customer_cart', $cart);

        return back()->with('status', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->whereKey($data['product_id'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->firstOrFail();

        $cart = $request->session()->get('customer_cart', []);
        abort_unless(array_key_exists($product->id, $cart), 404);

        $cart[$product->id] = min($data['quantity'], $product->stock);
        $request->session()->put('customer_cart', $cart);

        return back()->with('status', 'Jumlah keranjang diperbarui.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = $request->session()->get('customer_cart', []);
        unset($cart[$data['product_id']]);

        $request->session()->put('customer_cart', $cart);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }
}

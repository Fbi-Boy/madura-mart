<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $items = collect();   // ← pakai Collection
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            $subtotal = (float) $product->price * (int) $quantity;
            $total += $subtotal;

            $items->push([
                'product'  => $product,
                'quantity' => (int) $quantity,
                'subtotal' => $subtotal,
            ]);
        }

        return view('customer.cart.index', compact('items', 'total'));
    }

    /**
     * Tambah produk ke keranjang.
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);

        if ($product->stock < $quantity) {
            return back()->with('status', 'Stok tidak mencukupi.');
        }

        $cart = session()->get('cart', []);
        $currentQty = (int) ($cart[$product->id] ?? 0);
        $newQty = min($currentQty + $quantity, $product->stock);

        $cart[$product->id] = $newQty;
        session()->put('cart', $cart);

        return redirect()->route('customer.cart.index')
            ->with('status', 'Produk ditambahkan ke keranjang.');
    }

    /**
     * Update quantity produk di keranjang.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::find($validated['product_id']);
        $quantity = min((int) $validated['quantity'], $product->stock);

        $cart = session()->get('cart', []);
        $cart[$product->id] = $quantity;
        session()->put('cart', $cart);

        return back()->with('status', 'Keranjang diperbarui.');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $cart = session()->get('cart', []);
        unset($cart[$validated['product_id']]);
        session()->put('cart', $cart);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Request $request, Order $order): View
    {
        $customer = $this->customer($request);

        abort_unless($customer && $order->customer_id === $customer->id, 403);
        abort_if($order->status === 'cancelled', 422, 'Pesanan sudah dibatalkan.');
        abort_if($order->payment_status === 'paid', 422, 'Pembayaran pesanan sudah dikonfirmasi.');

        return view('customer.payment.index', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        $customer = $this->customer($request);

        abort_unless($customer && $order->customer_id === $customer->id, 403);
        abort_if($order->status === 'cancelled', 422, 'Pesanan sudah dibatalkan.');
        abort_if($order->payment_status === 'paid', 422, 'Pembayaran pesanan sudah dikonfirmasi.');

        $validated = $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $previousProof = $order->payment_proof;
        $path = $validated['payment_proof']->store('payment-proofs', 'local');

        try {
            $order->update([
                'payment_status' => 'pending',
                'payment_proof' => $path,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        if ($previousProof && $previousProof !== $path) {
            Storage::disk('local')->delete($previousProof);
        }

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('status', 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi.');
    }

    private function customer(Request $request): ?Customer
    {
        return Customer::query()
            ->where('email', $request->user()->email)
            ->where('is_active', true)
            ->first();
    }
}

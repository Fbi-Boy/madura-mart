<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentVerificationController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with('customer:id,name,email')
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_proof')
            ->latest('order_date')
            ->paginate(10);

        return view('admin.payment-verification.index', compact('orders'));
    }

    public function downloadProof(Order $order)
    {
        abort_unless($order->payment_proof, 404);

        return Storage::disk('local')->download(
            $order->payment_proof,
            basename($order->payment_proof),
        );
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:paid,rejected'],
        ]);

        abort_if($order->status === 'cancelled', 422, 'Pesanan sudah dibatalkan.');
        abort_unless($order->payment_proof, 422, 'Bukti pembayaran belum tersedia.');

        $order->update([
            'payment_status' => $validated['payment_status'],
        ]);

        ActivityLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => $validated['payment_status'] === 'paid'
                ? 'payment.verified'
                : 'payment.rejected',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => $validated['payment_status'] === 'paid'
                ? "Pembayaran order {$order->order_number} dikonfirmasi."
                : "Bukti pembayaran order {$order->order_number} ditolak.",
            'metadata' => [
                'payment_status' => $validated['payment_status'],
                'payment_method' => $order->payment_method,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with(
            'status',
            $validated['payment_status'] === 'paid'
                ? 'Pembayaran berhasil dikonfirmasi.'
                : 'Bukti pembayaran ditolak. Customer dapat mengirim ulang.',
        );
    }
}

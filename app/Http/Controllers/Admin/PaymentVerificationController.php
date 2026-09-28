<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\PaymentStateMachine;

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
        abort_if($order->payment_status !== 'pending', 422, 'Pembayaran sudah diproses.');
        abort_if($order->status === 'cancelled', 422, 'Pesanan sudah dibatalkan.');
        abort_unless($order->payment_proof, 422, 'Bukti pembayaran belum tersedia.');

        $validated = $request->validate([
            'payment_status' => ['required', 'in:paid,rejected'],
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $order, $validated): void {
            $lockedOrder = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            PaymentStateMachine::review(
                $lockedOrder,
                $validated['payment_status'],
                $request->user(),
                $validated['rejection_reason'] ?? null,
            );
        });

        return back()->with(
            'status',
            $validated['payment_status'] === 'paid'
                ? 'Pembayaran berhasil dikonfirmasi.'
                : 'Bukti pembayaran ditolak. Customer dapat mengirim ulang.',
        );
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PaymentStateMachine
{
    public static function submitProof(Order $order): Order
    {
        if ($order->payment_status === 'paid') {
            throw ValidationException::withMessages([
                'payment_proof' => 'Pembayaran yang sudah dikonfirmasi tidak dapat dikirim ulang.',
            ]);
        }

        if ($order->status === 'cancelled') {
            throw ValidationException::withMessages([
                'payment_proof' => 'Pesanan yang sudah dibatalkan tidak dapat dibayar.',
            ]);
        }

        $order->update([
            'payment_status' => 'pending',
            'payment_rejection_reason' => null,
        ]);

        return $order->refresh();
    }

    public static function review(
        Order $order,
        string $status,
        User $user,
        ?string $rejectionReason = null,
    ): Order {
        if ($order->status === 'cancelled') {
            throw ValidationException::withMessages([
                'payment_status' => 'Pesanan sudah dibatalkan.',
            ]);
        }

        if ($order->payment_status !== 'pending' || !$order->payment_proof) {
            throw ValidationException::withMessages([
                'payment_status' => 'Pembayaran ini tidak sedang menunggu verifikasi.',
            ]);
        }

        if (!in_array($status, ['paid', 'rejected'], true)) {
            throw ValidationException::withMessages([
                'payment_status' => 'Status pembayaran tidak valid.',
            ]);
        }

        if ($status === 'rejected' && trim((string) $rejectionReason) === '') {
            throw ValidationException::withMessages([
                'rejection_reason' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        $order->update([
            'payment_status' => $status,
            'payment_rejection_reason' => $status === 'rejected' ? trim($rejectionReason) : null,
        ]);

        ActivityLogService::record(
            $status === 'paid' ? 'payment.verified' : 'payment.rejected',
            $status === 'paid'
                ? "Pembayaran order {$order->order_number} dikonfirmasi."
                : "Bukti pembayaran order {$order->order_number} ditolak.",
            $order,
            [
                'from' => 'pending',
                'to' => $status,
                'payment_status' => $status,
                'payment_method' => $order->payment_method,
                'rejection_reason' => $status === 'rejected' ? $order->payment_rejection_reason : null,
            ],
        );

        return $order->refresh();
    }
}

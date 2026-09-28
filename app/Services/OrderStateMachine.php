<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class OrderStateMachine
{
    private const TRANSITIONS = [
        'pending' => ['processing', 'cancelled', 'failed'],
        'processing' => ['shipped', 'failed'],
        'shipped' => ['delivered', 'failed'],
        'delivered' => [],
        'cancelled' => [],
        'failed' => [],
    ];

    public static function transition(
        Order $order,
        string $to,
        ?User $user = null,
        ?string $note = null,
    ): Order {
        $from = $order->status;

        if ($from === $to) {
            return $order;
        }

        if (!in_array($to, self::TRANSITIONS[$from] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => "Transisi order {$from} → {$to} tidak diizinkan.",
            ]);
        }

        $order->update(['status' => $to]);

        $order->statusHistories()->create([
            'user_id' => $user?->id,
            'from_status' => $from,
            'to_status' => $to,
            'note' => $note,
        ]);

        return $order->refresh();
    }

    public static function recordInitial(Order $order, ?User $user = null, ?string $note = null): void
    {
        $order->statusHistories()->create([
            'user_id' => $user?->id,
            'from_status' => null,
            'to_status' => $order->status,
            'note' => $note,
        ]);
    }
}

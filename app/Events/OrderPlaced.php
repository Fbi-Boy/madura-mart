<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $order_id;
    public float $total;
    public string $user_name;

    public function __construct(
        int $orderId,
        float $total,
        string $userName,
        public int $userId,
        public string $invoice = ''
    ) {
        $this->order_id = $orderId;
        $this->total = $total;
        $this->user_name = $userName;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order_id,
            'total' => $this->total,
            'user_name' => $this->user_name,
            'invoice' => $this->invoice,
        ];
    }
}

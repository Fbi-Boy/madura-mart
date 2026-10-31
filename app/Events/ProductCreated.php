<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $productId,
        public string $productName,
        public string $createdBy,
        public int $createdById,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('super-admin.products'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.created';
    }

    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'created_by' => $this->createdBy,
            'created_by_id' => $this->createdById,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}

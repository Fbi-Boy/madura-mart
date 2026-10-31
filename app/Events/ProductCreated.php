<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Product $product)
    {
    }

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
            'id' => $this->product->id,
            'name' => $this->product->name,
            'sku' => $this->product->sku,
            'category' => $this->product->category?->name,
            'price' => (float) $this->product->price,
            'stock' => $this->product->stock,
            'unit' => $this->product->unit,
            'is_active' => $this->product->is_active,
            'created_at' => $this->product->created_at?->toISOString(),
        ];
    }
}

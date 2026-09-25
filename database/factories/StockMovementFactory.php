<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => 'adjustment',
            'quantity' => 0,
            'reference_type' => null,
            'reference_id' => null,
            'notes' => null,
            'occurred_at' => now(),
        ];
    }
}

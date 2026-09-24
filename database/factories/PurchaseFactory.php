<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'invoice' => 'PO-'.fake()->unique()->numerify('#####'),
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(['role' => 'admin']),
            'purchase_date' => now(),
            'total' => fake()->numberBetween(10000, 500000),
            'status' => 'received',
            'notes' => null,
        ];
    }
}

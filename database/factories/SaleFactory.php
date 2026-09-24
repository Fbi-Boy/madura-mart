<?php

namespace Database\Factories;

use App\Models\CashierShift;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'invoice' => 'INV-'.fake()->unique()->numerify('#####'),
            'customer_id' => null,
            'user_id' => User::factory(),
            'shift_id' => CashierShift::factory(),
            'sale_date' => now(),
            'total' => fake()->numberBetween(10000, 500000),
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'qris']),
            'status' => 'paid',
            'notes' => null,
        ];
    }
}

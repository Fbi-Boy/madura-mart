<?php

namespace Database\Factories;

use App\Models\CashierShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierShiftFactory extends Factory
{
    protected $model = CashierShift::class;

    public function definition(): array
    {
        return [
            'shift_number' => 'SHIFT-'.fake()->unique()->numerify('#####'),
            'user_id' => User::factory()->state(['role' => 'kasir']),
            'opened_at' => now(),
            'opening_cash' => 100000,
            'closed_at' => null,
            'closing_cash' => null,
            'expected_cash' => null,
            'closing_notes' => null,
            'status' => 'open',
        ];
    }
}

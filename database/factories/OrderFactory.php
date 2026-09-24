<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Courier;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Order> */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => 'ORD-'.fake()->unique()->numerify('#####'),
            'customer_id' => Customer::factory(),
            'courier_id' => Courier::factory(),
            'order_date' => now(),
            'total' => fake()->randomFloat(2, 25000, 750000),
            'status' => 'pending',
            'delivery_address' => fake()->address(),
            'notes' => null,
        ];
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }
}

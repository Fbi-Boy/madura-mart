<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'sku' => 'MM-'.fake()->unique()->numerify('####'),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'price' => fake()->numberBetween(5000, 250000),
            'stock' => fake()->numberBetween(0, 100),
            'unit' => fake()->randomElement(['pcs', 'box', 'pack', 'bottle']),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}

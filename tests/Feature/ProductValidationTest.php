<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_requires_core_fields(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/products', [])
            ->assertSessionHasErrors(['category_id', 'sku', 'name', 'price', 'stock', 'unit']);
    }

    public function test_product_sku_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['sku' => 'MM-DUP-001']);

        $this->actingAs($user)
            ->post('/admin/products', [
                'category_id' => $product->category_id,
                'sku' => 'MM-DUP-001',
                'name' => 'Produk Duplikat',
                'price' => 10000,
                'stock' => 1,
                'unit' => 'pcs',
            ])
            ->assertSessionHasErrors('sku');
    }
}

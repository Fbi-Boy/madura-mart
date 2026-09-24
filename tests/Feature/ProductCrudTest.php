<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($user)->post('/admin/products', [
            'category_id' => $category->id,
            'sku' => 'MM-TEST-001',
            'name' => 'Produk Baru',
            'price' => 15000,
            'stock' => 10,
            'unit' => 'pcs',
            'is_active' => '1',
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'sku' => 'MM-TEST-001',
            'name' => 'Produk Baru',
            'slug' => 'produk-baru',
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['name' => 'Lama']);

        $this->actingAs($user)->put("/admin/products/{$product->id}", [
            'category_id' => $product->category_id,
            'sku' => $product->sku,
            'name' => 'Baru',
            'price' => 25000,
            'stock' => 20,
            'unit' => 'pcs',
            'is_active' => '1',
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Baru',
            'slug' => 'baru',
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($user)->delete("/admin/products/{$product->id}")
            ->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

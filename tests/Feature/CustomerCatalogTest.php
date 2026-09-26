<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_browse_only_available_active_products(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $active = Product::factory()->create([
            'name' => 'Beras Madura',
            'is_active' => true,
            'stock' => 12,
        ]);

        Product::factory()->create([
            'name' => 'Produk Habis',
            'is_active' => true,
            'stock' => 0,
        ]);

        Product::factory()->create([
            'name' => 'Produk Nonaktif',
            'is_active' => false,
            'stock' => 10,
        ]);

        $this->actingAs($user)
            ->get(route('customer.catalog.index'))
            ->assertOk()
            ->assertViewIs('customer.catalog.index')
            ->assertViewHas('products', fn ($products) => $products->contains('id', $active->id)
                && $products->count() === 1);
    }

    public function test_customer_can_filter_catalog_by_search_and_category(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::factory()->create(['name' => 'Sembako', 'is_active' => true]);

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Beras Premium',
            'description' => 'Beras pilihan',
            'is_active' => true,
            'stock' => 5,
        ]);

        Product::factory()->create([
            'name' => 'Sabun',
            'is_active' => true,
            'stock' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('customer.catalog.index', ['q' => 'Beras', 'category' => $category->slug]))
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->count() === 1
                && $products->first()->name === 'Beras Premium');
    }

    public function test_customer_can_sort_catalog_by_price(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $expensive = Product::factory()->create([
            'name' => 'Produk Mahal',
            'price' => 90000,
            'is_active' => true,
            'stock' => 5,
        ]);

        $cheap = Product::factory()->create([
            'name' => 'Produk Murah',
            'price' => 10000,
            'is_active' => true,
            'stock' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('customer.catalog.index', ['sort' => 'price_asc']))
            ->assertOk()
            ->assertViewHas('sort', 'price_asc')
            ->assertViewHas('products', fn ($products) =>
                $products->first()->id === $cheap->id
                && $products->last()->id === $expensive->id
            );
    }

    public function test_customer_can_open_an_active_product_detail(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'name' => 'Minyak Goreng',
            'is_active' => true,
            'stock' => 7,
        ]);

        $this->actingAs($user)
            ->get(route('customer.catalog.show', $product->slug))
            ->assertOk()
            ->assertViewIs('customer.catalog.show')
            ->assertViewHas('product', fn ($item) => $item->id === $product->id);
    }

    public function test_non_customer_cannot_access_customer_catalog(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($user)
            ->get(route('customer.catalog.index'))
            ->assertForbidden();
    }
}

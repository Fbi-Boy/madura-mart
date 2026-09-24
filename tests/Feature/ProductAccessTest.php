<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_products(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get('/admin/products')->assertForbidden();
    }

    public function test_admin_can_view_products(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Sembako']);
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Beras']);

        $this->actingAs($user)->get('/admin/products')->assertOk()->assertSee('Beras');
    }
}

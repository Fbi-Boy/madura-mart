<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Product::factory()->create([
            'sku' => 'MM-001',
            'name' => 'Beras Madura',
            'stock' => 5,
            'is_active' => true,
        ]);
        Product::factory()->create([
            'sku' => 'MM-002',
            'name' => 'Gula Pasir',
            'stock' => 25,
            'is_active' => true,
        ]);
        Product::factory()->inactive()->create(['sku' => 'MM-003', 'stock' => 0]);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.produk', ['search' => 'Beras']))
            ->assertOk()
            ->assertViewHas('totalProducts', 3)
            ->assertViewHas('activeProducts', 2)
            ->assertViewHas('inactiveProducts', 1)
            ->assertViewHas('lowStock', 1)
            ->assertViewHas('outOfStock', 1)
            ->assertSee('MM-001')
            ->assertDontSee('MM-002');
    }

    public function test_kasir_cannot_monitor_products(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.produk'))
            ->assertForbidden();
    }
}

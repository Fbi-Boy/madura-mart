<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_stock_summary_and_search_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Product::factory()->create(['name' => 'Beras Premium', 'sku' => 'BR-001', 'stock' => 10]);
        Product::factory()->create(['name' => 'Gula', 'sku' => 'GL-001', 'stock' => 0]);

        $this->actingAs($admin)
            ->get(route('admin.report.stok', ['search' => 'Beras']))
            ->assertOk()
            ->assertViewHas('totalProducts', 2)
            ->assertViewHas('totalStock', 10)
            ->assertViewHas('outOfStock', 1);
    }

    public function test_kasir_cannot_access_stock_report(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.report.stok'))
            ->assertForbidden();
    }
}
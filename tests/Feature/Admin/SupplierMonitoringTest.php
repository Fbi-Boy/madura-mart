<?php

namespace Tests\Feature\Admin;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_suppliers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Supplier::factory()->create([
            'code' => 'SUP-001',
            'name' => 'Supplier Madura',
            'contact_person' => 'Fabi',
            'city' => 'Jember',
        ]);
        Supplier::factory()->create([
            'code' => 'SUP-002',
            'name' => 'Supplier Lain',
            'contact_person' => 'Budi',
            'city' => 'Probolinggo',
        ]);
        Supplier::factory()->inactive()->create(['code' => 'SUP-003']);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.supplier', ['search' => 'Fabi']))
            ->assertOk()
            ->assertViewHas('totalSuppliers', 3)
            ->assertViewHas('activeSuppliers', 2)
            ->assertViewHas('inactiveSuppliers', 1)
            ->assertSee('SUP-001')
            ->assertDontSee('SUP-002');
    }

    public function test_kasir_cannot_monitor_suppliers(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.supplier'))
            ->assertForbidden();
    }
}

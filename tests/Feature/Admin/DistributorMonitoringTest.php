<?php

namespace Tests\Feature\Admin;

use App\Models\Distributor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributorMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_distributors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Distributor::factory()->create(['code' => 'DST-001', 'name' => 'Distributor Madura', 'contact_person' => 'Fabi']);
        Distributor::factory()->create(['code' => 'DST-002', 'name' => 'Distributor Lain']);
        Distributor::factory()->inactive()->create(['code' => 'DST-003']);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.distributor', ['search' => 'Fabi']))
            ->assertOk()
            ->assertViewHas('totalDistributors', 3)
            ->assertViewHas('activeDistributors', 2)
            ->assertViewHas('inactiveDistributors', 1)
            ->assertSee('DST-001')
            ->assertDontSee('DST-002');
    }

    public function test_kasir_cannot_monitor_distributors(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->get(route('admin.monitoring.distributor'))->assertForbidden();
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_clients(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Customer::factory()->create(['code' => 'CUS-001', 'name' => 'Fabi Client']);
        Customer::factory()->create(['code' => 'CUS-002', 'name' => 'Other Client']);
        Customer::factory()->inactive()->create(['code' => 'CUS-003']);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.client', ['search' => 'Fabi']))
            ->assertOk()
            ->assertViewHas('totalClients', 3)
            ->assertViewHas('activeClients', 2)
            ->assertViewHas('inactiveClients', 1)
            ->assertSee('CUS-001')
            ->assertDontSee('CUS-002');
    }

    public function test_kasir_cannot_monitor_clients(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->get(route('admin.monitoring.client'))->assertForbidden();
    }
}

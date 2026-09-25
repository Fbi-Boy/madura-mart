<?php

namespace Tests\Feature\Admin;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_search_couriers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Courier::factory()->create([
            'code' => 'KUR-001',
            'name' => 'Kurir Madura',
            'phone' => '081234567890',
            'vehicle_number' => 'P 1234 AB',
        ]);
        Courier::factory()->create([
            'code' => 'KUR-002',
            'name' => 'Kurir Lain',
            'phone' => '089999999999',
            'vehicle_number' => 'P 5678 CD',
        ]);
        Courier::factory()->inactive()->create(['code' => 'KUR-003']);

        $this->actingAs($admin)
            ->get(route('admin.monitoring.kurir', ['search' => 'P 1234 AB']))
            ->assertOk()
            ->assertViewHas('totalCouriers', 3)
            ->assertViewHas('activeCouriers', 2)
            ->assertViewHas('inactiveCouriers', 1)
            ->assertSee('KUR-001')
            ->assertDontSee('KUR-002');
    }

    public function test_kasir_cannot_monitor_couriers(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('admin.monitoring.kurir'))
            ->assertForbidden();
    }
}

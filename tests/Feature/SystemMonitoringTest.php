<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_system_monitoring(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $actor = User::factory()->create(['role' => 'kasir']);

        ActivityLog::query()->create([
            'user_id' => $actor->id,
            'action' => 'sale.created',
            'description' => 'Transaksi penjualan tercatat.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.system-monitoring.index'))
            ->assertOk()
            ->assertViewIs('admin.system-monitoring.index')
            ->assertViewHas('healthyChecks', 4)
            ->assertViewHas('todayActivityCount', 1)
            ->assertSee('sale.created')
            ->assertSee('Transaksi penjualan tercatat.');
    }

    public function test_super_admin_can_view_system_monitoring(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get(route('admin.system-monitoring.index'))
            ->assertOk();
    }

    public function test_non_admin_roles_are_denied_from_system_monitoring(): void
    {
        foreach (['gudang', 'kasir', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.system-monitoring.index'))
                ->assertForbidden();
        }
    }
}

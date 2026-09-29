<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DemoSystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionDriftHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_system_seeder_uses_registered_stock_permission(): void
    {
        foreach ([
            ['admin', 'admin@maduramart.test'],
            ['super-admin', 'superadmin@maduramart.test'],
            ['gudang', 'gudang@maduramart.test'],
            ['kasir', 'kasir@maduramart.test'],
            ['purchasing', 'purchasing@maduramart.test'],
            ['kurir', 'kurir@maduramart.test'],
        ] as [$role, $email]) {
            User::factory()->create([
                'name' => ucfirst($role),
                'email' => $email,
                'role' => $role,
            ]);
        }

        Product::factory()->create();

        $this->seed(DemoSystemSeeder::class);

        $this->assertDatabaseHas('permission_overrides', [
            'role' => 'gudang',
            'permission' => 'stock.manage',
            'enabled' => true,
        ]);

        $this->assertDatabaseMissing('permission_overrides', [
            'role' => 'gudang',
            'permission' => 'inventory.manage',
        ]);
    }

    public function test_monitoring_navigation_follows_system_monitoring_permission(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->view('layouts.navigation')
            ->assertSee(route('admin.monitoring.penjualan'));

        PermissionOverride::create([
            'role' => 'admin',
            'permission' => 'system-monitoring.view',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->view('layouts.navigation')
            ->assertDontSee(route('admin.monitoring.penjualan'))
            ->assertDontSee(route('admin.monitoring.distributor'))
            ->assertDontSee(route('admin.monitoring.client'))
            ->assertDontSee(route('admin.monitoring.kurir'))
            ->assertDontSee(route('admin.monitoring.supplier'));
    }

    public function test_dashboard_permission_override_blocks_dashboard_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        PermissionOverride::query()->create([
            'role' => 'admin',
            'permission' => 'dashboard.view',
            'enabled' => false,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_permission_is_available_to_default_roles(): void
    {
        foreach ([
            'admin',
            'super-admin',
            'gudang',
            'kasir',
            'purchasing',
            'kurir',
            'customer',
        ] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertOk();
        }
    }

}

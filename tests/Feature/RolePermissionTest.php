<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_can_view_role_permission_workspace(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($superAdmin)
            ->get(route('admin.roles.index'))
            ->assertOk()
            ->assertViewIs('admin.roles.index')
            ->assertViewHas('roles')
            ->assertSeeText('Role & Permission')
            ->assertSee('role-management.view');

        $this->actingAs($admin)
            ->get(route('admin.roles.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.roles.index'))
            ->assertForbidden();
    }

    public function test_role_permission_workspace_exposes_configured_role_matrix(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get(route('admin.roles.index'))
            ->assertOk()
            ->assertViewHas('roles', fn ($roles) =>
                $roles->count() === 7
                && $roles['super-admin']['permissions']->contains('audit-log.view')
                && $roles['admin']['permissions']->contains('reports.view')
                && $roles['customer']['permissions']->contains('orders.manage')
                && ! $roles['customer']['permissions']->contains('audit-log.view')
            );
    }

    public function test_super_admin_can_override_and_restore_a_role_permission(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.system-monitoring.index'))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->patch(route('admin.roles.update'), [
                'permissions' => [
                    'admin' => [
                        'system-monitoring.view' => false,
                    ],
                ],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->actingAs($admin)
            ->get(route('admin.system-monitoring.index'))
            ->assertForbidden();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $superAdmin->id,
            'action' => 'permission.updated',
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.roles.update'), [
                'permissions' => [
                    'admin' => [
                        'system-monitoring.view' => true,
                    ],
                ],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->actingAs($admin)
            ->get(route('admin.system-monitoring.index'))
            ->assertOk();
    }

    public function test_role_permission_update_is_restricted_to_super_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.roles.update'), [
                'permissions' => [
                    'admin' => [
                        'system-monitoring.view' => false,
                    ],
                ],
            ])
            ->assertForbidden();
    }

}

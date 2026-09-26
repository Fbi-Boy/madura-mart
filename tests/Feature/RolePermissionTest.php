<?php

namespace Tests\Feature;

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
}

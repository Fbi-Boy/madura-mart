<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_role_permission_matrix(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->get(route('admin.roles.permissions.index'))
            ->assertOk()
            ->assertViewIs('admin.roles.permissions')
            ->assertViewHas('roles', fn ($roles) => $roles->contains('super-admin'))
            ->assertViewHas('permissions', fn ($permissions) => $permissions->has('purchases.manage'))
            ->assertViewHas('rolePermissions', fn ($matrix) =>
                in_array('purchases.manage', $matrix['purchasing'] ?? [], true)
                && ! in_array('purchases.manage', $matrix['customer'] ?? [], true)
            )
            ->assertSee('Purchase Orders');
    }

    public function test_non_super_admin_cannot_view_role_permission_matrix(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.roles.permissions.index'))
            ->assertForbidden();
    }

    public function test_guest_cannot_view_role_permission_matrix(): void
    {
        $this->get(route('admin.roles.permissions.index'))
            ->assertRedirect(route('login'));
    }
}

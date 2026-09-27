<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_can_manage_users(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($superAdmin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_super_admin_can_filter_users_by_search_role_and_status(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        User::factory()->create(['name' => 'Kasir Aktif', 'role' => 'kasir', 'is_active' => true]);
        User::factory()->create(['name' => 'Kasir Nonaktif', 'role' => 'kasir', 'is_active' => false]);
        User::factory()->create(['name' => 'Gudang', 'role' => 'gudang', 'is_active' => true]);

        $this->actingAs($superAdmin)
            ->get(route('admin.users.index', ['search' => 'Kasir', 'role' => 'kasir', 'status' => 'active']))
            ->assertOk()
            ->assertViewHas('users', fn ($users) =>
                $users->total() === 1
                && $users->first()->name === 'Kasir Aktif'
            );
    }

    public function test_super_admin_can_deactivate_and_reactivate_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create(['role' => 'kasir', 'is_active' => true]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.status', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.status', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => true]);
    }

    public function test_inactive_users_are_blocked_from_authenticated_routes(): void
    {
        $user = User::factory()->create(['role' => 'kasir', 'is_active' => false]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_super_admin_can_reset_a_user_password(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.reset-password', $user), [
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('admin.users.edit', $user));

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_super_admin_cannot_delete_the_last_active_super_admin(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->delete(route('admin.users.destroy', $superAdmin))
            ->assertStatus(422);

        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
    }
}

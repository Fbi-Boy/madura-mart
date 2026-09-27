<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminUserLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_can_manage_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($superAdmin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertViewIs('admin.users.index');
    }

    public function test_super_admin_can_deactivate_and_reactivate_a_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create(['role' => 'kasir', 'is_active' => true]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.toggle-active', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertFalse($user->fresh()->is_active);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.toggle-active', $user->fresh()))
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_super_admin_cannot_deactivate_their_own_account(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin', 'is_active' => true]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.toggle-active', $superAdmin))
            ->assertStatus(422);

        $this->assertTrue($superAdmin->fresh()->is_active);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@maduramart.test',
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_super_admin_can_reset_another_users_password(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);
        $oldHash = $user->password;

        $this->actingAs($superAdmin)
            ->post(route('admin.users.reset-password', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $freshUser = $user->fresh();

        $this->assertNotSame($oldHash, $freshUser->password);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'user.password_reset',
            'subject_id' => $user->id,
        ]);
    }

    public function test_super_admin_cannot_reset_their_own_password_from_user_management(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->post(route('admin.users.reset-password', $superAdmin))
            ->assertStatus(422);
    }
}

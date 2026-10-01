<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMasterCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'Staff Baru', 'email' => 'staff@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'kasir',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'staff@example.com', 'role' => 'kasir']);
    }

    public function test_duplicate_email_is_rejected_when_creating_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($superAdmin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Duplicate',
                'email' => 'taken@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'kasir',
            ])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['name' => 'Duplicate']);
    }

    public function test_super_admin_can_update_user_without_changing_password(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($superAdmin)->put(route('admin.users.update', $user), [
            'name' => 'Staff Updated', 'email' => $user->email, 'role' => 'kurir',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Staff Updated', 'role' => 'kurir']);
    }

    public function test_super_admin_cannot_delete_own_account(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->delete(route('admin.users.destroy', $superAdmin))
            ->assertStatus(422);
    }

    public function test_admin_cannot_manage_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}

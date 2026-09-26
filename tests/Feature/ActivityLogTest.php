<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_activity_logs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::query()->create([
            'user_id' => $admin->id,
            'action' => 'test.activity',
            'description' => 'Aktivitas pengujian.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertViewIs('admin.activity-logs.index')
            ->assertSee('test.activity')
            ->assertSee('Aktivitas pengujian.');
    }

    public function test_user_lifecycle_actions_are_recorded(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@maduramart.test',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Staff Baru',
                'email' => 'staff@maduramart.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'kasir',
            ])
            ->assertRedirect(route('admin.users.index'));

        $created = User::query()->where('email', 'staff@maduramart.test')->firstOrFail();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'user.created',
            'subject_type' => User::class,
            'subject_id' => $created->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $created), [
                'name' => 'Staff Diperbarui',
                'email' => 'staff@maduramart.test',
                'password' => '',
                'password_confirmation' => '',
                'role' => 'purchasing',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'user.updated',
            'subject_id' => $created->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $created))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'user.deleted',
            'subject_id' => $created->id,
        ]);
    }

    public function test_non_admin_cannot_view_activity_logs(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($user)
            ->get(route('admin.activity-logs.index'))
            ->assertForbidden();
    }
}

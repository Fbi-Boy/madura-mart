<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_super_admin_can_open_audit_log(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($superAdmin)
            ->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertViewIs('admin.audit-logs.index');

        $this->actingAs($admin)
            ->get(route('admin.audit-logs.index'))
            ->assertForbidden();
    }

    public function test_audit_log_shows_only_activity_with_subjects_and_supports_filters(): void
    {
        $actor = User::factory()->create(['role' => 'super-admin']);
        $otherActor = User::factory()->create(['role' => 'admin']);

        ActivityLog::factory()->create([
            'user_id' => $actor->id,
            'action' => 'user.updated',
            'subject_type' => User::class,
            'subject_id' => $otherActor->id,
            'description' => 'Role user berubah.',
            'metadata' => ['role_before' => 'admin', 'role_after' => 'gudang'],
        ]);

        ActivityLog::factory()->create([
            'user_id' => $actor->id,
            'action' => 'system.viewed',
            'subject_type' => null,
            'subject_id' => null,
            'description' => 'Tidak termasuk audit.',
        ]);

        $this->actingAs($actor)
            ->get(route('admin.audit-logs.index', ['action' => 'user.updated', 'user_id' => $actor->id]))
            ->assertOk()
            ->assertViewHas('logs', fn ($logs) => $logs->total() === 1)
            ->assertSee('role_before')
            ->assertSee('role_after')
            ->assertSee('user.updated');
    }
}

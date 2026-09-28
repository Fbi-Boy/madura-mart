<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminControlTest extends TestCase
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
            ->assertOk();
    }

    public function test_only_super_admin_can_manage_role_permissions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($admin)
            ->get(route('admin.roles.index'))
            ->assertForbidden();

        $this->actingAs($superAdmin)
            ->get(route('admin.roles.index'))
            ->assertOk();
    }

    public function test_role_permission_override_can_be_created_and_restored(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->patch(route('admin.roles.update'), [
                'permissions' => [
                    'admin' => [
                        'reports.view' => false,
                    ],
                ],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseHas('permission_overrides', [
            'role' => 'admin',
            'permission' => 'reports.view',
            'enabled' => false,
            'updated_by' => $superAdmin->id,
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.roles.update'), [
                'permissions' => [
                    'admin' => [
                        'reports.view' => true,
                    ],
                ],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseMissing('permission_overrides', [
            'role' => 'admin',
            'permission' => 'reports.view',
        ]);
    }

    public function test_super_admin_cannot_remove_themselves_from_the_control_plane(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin', 'is_active' => true]);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.status', $superAdmin))
            ->assertStatus(422);

        $this->actingAs($superAdmin)
            ->patch(route('admin.users.update', $superAdmin), [
                'name' => $superAdmin->name,
                'email' => $superAdmin->email,
                'role' => 'admin',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $superAdmin->id,
            'role' => 'super-admin',
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_update_system_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->patch(route('admin.settings.update'), [
                'store_name' => 'Madura Mart Demo',
                'store_phone' => '081234567890',
                'store_email' => 'store@maduramart.test',
                'currency' => 'IDR',
                'order_prefix' => 'MM-',
                'minimum_order' => '10000',
                'tax_percent' => '11',
                'discount_percent' => '0',
                'payment_methods' => 'QRIS, Transfer Bank',
                'bank_name' => 'Bank Demo',
                'bank_account' => '1234567890',
                'shipping_enabled' => '1',
                'shipping_fee' => '10000',
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('system_settings', [
            'key' => 'store_name',
            'value' => 'Madura Mart Demo',
        ]);
    }

    public function test_super_admin_can_view_audit_and_system_monitoring(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)
            ->get(route('admin.audit-logs.index'))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->get(route('admin.system-monitoring.index'))
            ->assertOk();
    }

    public function test_admin_cannot_update_system_settings_or_view_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), [
                'store_name' => 'Blocked',
            ])
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.audit-logs.index'))
            ->assertForbidden();
    }
}

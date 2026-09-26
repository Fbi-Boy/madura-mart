<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_system_settings(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertViewIs('admin.settings.index')
            ->assertViewHas('settings')
            ->assertSee('Madura Mart');
    }

    public function test_super_admin_can_update_system_settings(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->patch(route('admin.settings.update'), [
                'store_name' => 'Madura Mart Jember',
                'store_phone' => '08123456789',
                'store_email' => 'toko@example.test',
                'tax_percent' => '11',
                'discount_percent' => '5',
                'shipping_fee' => '10000',
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('system_settings', [
            'key' => 'store_name',
            'value' => 'Madura Mart Jember',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'key' => 'tax_percent',
            'value' => '11',
        ]);
    }

    public function test_non_admin_roles_cannot_access_system_settings(): void
    {
        foreach (['gudang', 'kasir', 'purchasing', 'kurir', 'customer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.settings.index'))
                ->assertForbidden();
        }
    }

    public function test_invalid_email_is_rejected(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->from(route('admin.settings.index'))
            ->patch(route('admin.settings.update'), [
                'store_name' => 'Madura Mart',
                'store_email' => 'not-an-email',
            ])
            ->assertSessionHasErrors('store_email');

        $this->assertDatabaseCount('system_settings', 0);
    }
}

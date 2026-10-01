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

    public function test_settings_page_exposes_all_configuration_groups(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Identitas Toko')
            ->assertSee('Transaksi')
            ->assertSee('Pembayaran')
            ->assertSee('Pengiriman');
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
                'currency' => 'IDR',
                'order_prefix' => 'MM-',
                'minimum_order' => '25000',
                'payment_methods' => 'QRIS, Transfer Bank',
                'bank_name' => 'Bank Madura',
                'bank_account' => '1234567890',
                'shipping_enabled' => '1',
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

        $this->assertDatabaseHas('system_settings', [
            'key' => 'shipping_enabled',
            'value' => '1',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'system-settings.updated',
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

    public function test_only_super_admin_can_update_system_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), ['store_name' => 'Tidak Boleh'])
            ->assertForbidden();
    }

    public function test_negative_transaction_values_are_rejected(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($user)
            ->from(route('admin.settings.index'))
            ->patch(route('admin.settings.update'), [
                'minimum_order' => '-1',
                'tax_percent' => '-5',
                'discount_percent' => '-10',
                'shipping_fee' => '-1000',
            ])
            ->assertSessionHasErrors([
                'minimum_order',
                'tax_percent',
                'discount_percent',
                'shipping_fee',
            ]);

        $this->assertDatabaseCount('system_settings', 0);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $user = User::factory()->create(['role' => 'super-admin']);

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

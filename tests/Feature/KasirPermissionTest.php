<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function cashier(): User
    {
        $user = User::factory()->create([
            'role' => 'kasir',
            'email' => 'permission-kasir@maduramart.test',
        ]);

        CashierShift::factory()->create([
            'user_id' => $user->id,
            'status' => 'open',
        ]);

        return $user;
    }

    private function revokeSalesPermission(User $user): void
    {
        PermissionOverride::create([
            'role' => 'kasir',
            'permission' => 'sales.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);
    }

    public function test_cashier_can_reach_transaction_workspace_with_default_permission(): void
    {
        $user = $this->cashier();

        $this->actingAs($user)
            ->get(route('kasir.transaksi-baru'))
            ->assertOk();
    }

    public function test_sales_permission_override_blocks_new_transaction(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.transaksi-baru'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_transaction_submission(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->post(route('kasir.transaksi-baru.store'), [])->assertForbidden();
    }


    public function test_sales_permission_override_blocks_transaction_history(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.riwayat-transaksi'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_returns_workspace(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.retur'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_new_return_form(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.retur.create'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_return_submission(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->post(route('kasir.retur.store'), [])->assertForbidden();
    }


    public function test_sales_permission_override_blocks_open_shift_workspace(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.buka-shift'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_open_shift_submission(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->post(route('kasir.buka-shift.store'), [])->assertForbidden();
    }


    public function test_sales_permission_override_blocks_close_shift_workspace(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.tutup-shift'))->assertForbidden();
    }


    public function test_sales_permission_override_blocks_close_shift_submission(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->post(route('kasir.tutup-shift.store'), [])->assertForbidden();
    }


    public function test_sales_permission_override_blocks_shift_history(): void
    {
        $user = $this->cashier();
        $this->revokeSalesPermission($user);

        $this->actingAs($user)->get(route('kasir.riwayat-shift'))->assertForbidden();
    }


    public function test_explicitly_enabled_sales_override_restores_cashier_access(): void
    {
        $user = $this->cashier();

        PermissionOverride::create([
            'role' => 'kasir',
            'permission' => 'sales.manage',
            'enabled' => true,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)->get(route('kasir.transaksi-baru'))->assertOk();
    }


    public function test_admin_cannot_use_cashier_routes_even_with_sales_permission(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('kasir.transaksi-baru'))->assertForbidden();
    }


    public function test_customer_cannot_use_cashier_routes_even_with_sales_permission(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get(route('kasir.transaksi-baru'))->assertForbidden();
    }


    public function test_every_cashier_route_requires_sales_permission(): void
    {
        $routes = [
            'kasir.transaksi-baru',
            'kasir.transaksi-baru.store',
            'kasir.riwayat-transaksi',
            'kasir.retur',
            'kasir.retur.create',
            'kasir.retur.store',
            'kasir.buka-shift',
            'kasir.buka-shift.store',
            'kasir.tutup-shift',
            'kasir.tutup-shift.store',
            'kasir.riwayat-shift',
        ];

        foreach ($routes as $name) {
            $route = app('router')->getRoutes()->getByName($name);

            $this->assertNotNull($route, $name);
            $this->assertContains('permission:sales.manage', $route->middleware(), $name);
        }
    }

}

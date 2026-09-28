<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function cashier(): User
    {
        return User::factory()->create([
            'role' => 'kasir',
            'email' => 'permission-kasir@maduramart.test',
        ]);
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
            ->assertRedirect();
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

}

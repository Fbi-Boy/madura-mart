<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchasingPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_can_access_purchase_and_supplier_workspace_with_default_permissions(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $this->actingAs($user)
            ->get(route('purchasing.purchases.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index'))
            ->assertOk();
    }

    public function test_purchase_permission_override_blocks_entire_purchase_workspace(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        PermissionOverride::create([
            'role' => 'purchasing',
            'permission' => 'purchases.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.purchases.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('purchasing.purchases.create'))
            ->assertForbidden();
    }

    public function test_supplier_permission_override_blocks_supplier_workspace(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        PermissionOverride::create([
            'role' => 'purchasing',
            'permission' => 'suppliers.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index'))
            ->assertForbidden();

        PermissionOverride::create([
            'role' => 'purchasing',
            'permission' => 'suppliers.manage',
            'enabled' => true,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index'))
            ->assertOk();
    }
}

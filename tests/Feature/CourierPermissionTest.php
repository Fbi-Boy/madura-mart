<?php

namespace Tests\Feature;

use App\Models\PermissionOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurir_can_access_delivery_workspace_with_default_permission(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'permission-kurir@maduramart.test',
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.riwayat'))
            ->assertOk();
    }

    public function test_delivery_permission_override_blocks_entire_courier_workspace(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'blocked-kurir@maduramart.test',
        ]);

        PermissionOverride::create([
            'role' => 'kurir',
            'permission' => 'deliveries.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('kurir.pengiriman.riwayat'))
            ->assertForbidden();
    }

    public function test_delivery_permission_override_blocks_status_update(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'email' => 'status-kurir@maduramart.test',
        ]);

        PermissionOverride::create([
            'role' => 'kurir',
            'permission' => 'deliveries.manage',
            'enabled' => false,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->patch(route('kurir.pengiriman.status', 1), ['status' => 'processing'])
            ->assertForbidden();
    }
}

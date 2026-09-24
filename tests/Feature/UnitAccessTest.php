<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_unit_master_data(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get(route('admin.units.index'))->assertForbidden();
    }

    public function test_admin_can_access_unit_master_data(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('admin.units.index'))->assertOk();
    }
}
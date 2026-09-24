<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_role_cannot_access_customer_master_data(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('admin.customers.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_customer_master_data(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.customers.index'))
            ->assertOk();
    }
}
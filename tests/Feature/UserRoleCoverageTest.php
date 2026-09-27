<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_gudang_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'Petugas Gudang', 'email' => 'gudang@maduramart.test',
            'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'gudang',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'gudang@maduramart.test', 'role' => 'gudang']);
    }

    public function test_super_admin_can_create_customer_user(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super-admin']);

        $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'Customer Madura', 'email' => 'customer@maduramart.test',
            'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'customer',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'customer@maduramart.test', 'role' => 'customer']);
    }

    public function test_non_super_admin_cannot_create_users_with_any_role(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('admin.users.store'), [
                'name' => 'Blocked User', 'email' => 'blocked@maduramart.test',
                'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'gudang',
            ])
            ->assertForbidden();
    }
}

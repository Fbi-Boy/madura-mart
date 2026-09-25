<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_gudang_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Petugas Gudang',
            'email' => 'gudang@maduramart.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'gudang',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'gudang@maduramart.test',
            'role' => 'gudang',
        ]);
    }

    public function test_admin_can_create_customer_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Customer Madura',
            'email' => 'customer@maduramart.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'customer@maduramart.test',
            'role' => 'customer',
        ]);
    }

    public function test_non_admin_cannot_create_users_with_any_role(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->post(route('admin.users.store'), [
                'name' => 'Blocked User',
                'email' => 'blocked@maduramart.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'gudang',
            ])
            ->assertForbidden();
    }
}

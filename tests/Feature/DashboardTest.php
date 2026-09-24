<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function assertDashboardForRole(string $role, string $view): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs($view);
    }

    public function test_purchasing_users_see_the_purchasing_dashboard(): void
    {
        $this->assertDashboardForRole('purchasing', 'purchasing.dashboard');
    }

    public function test_admin_users_see_the_admin_dashboard(): void
    {
        $this->assertDashboardForRole('admin', 'admin.dashboard');
    }

    public function test_kurir_users_see_the_kurir_dashboard(): void
    {
        $this->assertDashboardForRole('kurir', 'kurir.dashboard');
    }

    public function test_customer_users_see_the_customer_dashboard(): void
    {
        $this->assertDashboardForRole('customer', 'customer.dashboard');
    }

    public function test_super_admin_users_see_the_super_admin_dashboard(): void
    {
        $this->assertDashboardForRole('super-admin', 'super-admin.dashboard');
    }
}

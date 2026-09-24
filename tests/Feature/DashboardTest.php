<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_users_see_the_purchasing_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('purchasing.dashboard');
    }

    public function test_admin_users_see_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('admin.dashboard');
    }
}

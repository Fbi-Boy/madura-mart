<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'login@maduramart.test',
            'password' => 'password',
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->post(route('login'), [
            'email' => 'login@maduramart.test',
            'password' => 'password',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_does_not_authenticate_user(): void
    {
        User::factory()->create([
            'email' => 'invalid@maduramart.test',
            'password' => 'password',
        ]);

        $this->post(route('login'), [
            'email' => 'invalid@maduramart.test',
            'password' => 'wrong-password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_registration_cannot_escalate_role_or_activation_state(): void
    {
        $this->post(route('register'), [
            'name' => 'Customer Baru',
            'email' => 'customer@maduramart.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'super-admin',
            'is_active' => false,
        ])
            ->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'customer@maduramart.test')->firstOrFail();

        $this->assertSame('customer', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_invalidates_authentication(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_inactive_user_is_rejected_from_authenticated_application_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();

        $this->assertAuthenticatedAs($user);
    }
}

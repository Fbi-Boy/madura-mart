<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_own_address_workspace(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@example.test',
        ]);

        Customer::factory()->create([
            'email' => $user->email,
            'address' => 'Jl. Lama 1',
            'city' => 'Jember',
        ]);

        $this->actingAs($user)
            ->get(route('customer.address.edit'))
            ->assertOk()
            ->assertSee('Jl. Lama 1')
            ->assertSee('Jember');
    }

    public function test_customer_can_update_own_address(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@example.test',
        ]);

        $customer = Customer::factory()->create([
            'email' => $user->email,
        ]);

        $this->actingAs($user)->patch(route('customer.address.update'), [
            'name' => 'Fabi',
            'phone' => '081234567890',
            'address' => 'Jl. Baru No. 10',
            'city' => 'Probolinggo',
        ])->assertRedirect(route('customer.address.edit'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Fabi',
            'phone' => '081234567890',
            'address' => 'Jl. Baru No. 10',
            'city' => 'Probolinggo',
        ]);
    }

    public function test_non_customer_cannot_access_address_workspace(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('customer.address.edit'))
            ->assertForbidden();
    }
}

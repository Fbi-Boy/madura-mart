<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_customer(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.customers.store'), [
            'code' => 'CUS-100',
            'name' => 'Customer Baru',
            'phone' => '081200000000',
            'email' => 'baru@example.com',
            'city' => 'Pamekasan',
            'is_active' => 1,
        ])->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseHas('customers', [
            'code' => 'CUS-100',
            'name' => 'Customer Baru',
        ]);
    }

    public function test_admin_can_update_customer(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $customer = Customer::factory()->create(['code' => 'CUS-101', 'name' => 'Lama']);

        $this->actingAs($user)->put(route('admin.customers.update', $customer), [
            'code' => 'CUS-101',
            'name' => 'Baru',
            'is_active' => 1,
        ])->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Baru']);
    }

    public function test_admin_can_delete_customer(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $customer = Customer::factory()->create();

        $this->actingAs($user)->delete(route('admin.customers.destroy', $customer))
            ->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
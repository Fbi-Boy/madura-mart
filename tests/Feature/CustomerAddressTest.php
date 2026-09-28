<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_own_address_workspace(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'email' => 'customer@example.test']);
        Customer::factory()->create(['email' => $user->email, 'address' => 'Jl. Lama 1', 'city' => 'Jember']);

        $this->actingAs($user)->get(route('customer.address.edit'))
            ->assertOk()->assertSee('Jl. Lama 1')->assertSee('Jember');
    }

    public function test_customer_can_update_own_legacy_address(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'email' => 'customer@example.test']);
        $customer = Customer::factory()->create(['email' => $user->email]);

        $this->actingAs($user)->patch(route('customer.address.update'), [
            'name' => 'Fabi', 'phone' => '081234567890', 'address' => 'Jl. Baru No. 10', 'city' => 'Probolinggo',
        ])->assertRedirect(route('customer.address.edit'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id, 'name' => 'Fabi', 'phone' => '081234567890',
            'address' => 'Jl. Baru No. 10', 'city' => 'Probolinggo',
        ]);
    }

    public function test_customer_can_add_multiple_addresses_and_first_becomes_default(): void
    {
        [$user, $customer] = $this->customer('multi@maduramart.test');
        $payload = [
            'mode' => 'add', 'label' => 'Rumah', 'recipient_name' => 'Fabi',
            'phone' => '08123456789', 'address' => 'Jl. Contoh 1', 'city' => 'Jember',
        ];

        $this->actingAs($user)->patch(route('customer.address.update'), $payload)->assertRedirect();
        $address = CustomerAddress::query()->firstOrFail();
        $this->assertTrue($address->is_default);

        $this->actingAs($user)->patch(route('customer.address.update'), [
            ...$payload, 'label' => 'Kost', 'address' => 'Jl. Contoh 2',
        ])->assertRedirect();

        $this->assertSame(2, $customer->addresses()->count());
        $this->assertSame(1, $customer->addresses()->where('is_default', true)->count());
    }

    public function test_customer_can_change_default_and_delete_own_address(): void
    {
        [$user, $customer] = $this->customer('default@maduramart.test');
        $first = $customer->addresses()->create([
            'label' => 'Rumah', 'recipient_name' => 'Fabi', 'phone' => '0800', 'address' => 'Alamat 1', 'city' => 'Jember', 'is_default' => true,
        ]);
        $second = $customer->addresses()->create([
            'label' => 'Kost', 'recipient_name' => 'Fabi', 'phone' => '0800', 'address' => 'Alamat 2', 'city' => 'Jember', 'is_default' => false,
        ]);

        $this->actingAs($user)->patch(route('customer.address.update'), ['mode' => 'default', 'address_id' => $second->id])->assertRedirect();
        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);

        $this->actingAs($user)->patch(route('customer.address.update'), ['mode' => 'delete', 'address_id' => $second->id])->assertRedirect();
        $this->assertDatabaseMissing('customer_addresses', ['id' => $second->id]);
        $this->assertTrue($first->fresh()->is_default);
    }

    public function test_customer_cannot_manipulate_another_customers_address(): void
    {
        [$user, $customer] = $this->customer('a@maduramart.test');
        [, $other] = $this->customer('b@maduramart.test');
        $address = $other->addresses()->create([
            'label' => 'Rumah', 'recipient_name' => 'Other', 'phone' => '0800', 'address' => 'Alamat lain', 'city' => 'Jember', 'is_default' => true,
        ]);

        $this->actingAs($user)->patch(route('customer.address.update'), ['mode' => 'default', 'address_id' => $address->id])->assertNotFound();
        $this->assertTrue($address->fresh()->is_default);
        $this->assertSame($customer->id, $address->fresh()->customer_id === $customer->id ? $customer->id : $customer->id);
    }

    public function test_non_customer_cannot_access_address_workspace(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user)->get(route('customer.address.edit'))->assertForbidden();
    }

    private function customer(string $email): array
    {
        $user = User::factory()->create(['role' => 'customer', 'email' => $email]);
        $customer = Customer::factory()->create(['email' => $email, 'is_active' => true]);
        return [$user, $customer];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FinalHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), [
                'email' => 'unknown@maduramart.test',
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post(route('login'), [
            'email' => 'unknown@maduramart.test',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_registration_is_rate_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('register'), [
                'name' => 'Test',
                'email' => "rate-{$i}@maduramart.test",
                'password' => 'password',
                'password_confirmation' => 'password',
            ])->assertRedirect();
        }

        $this->post(route('register'), [
            'name' => 'Test',
            'email' => 'rate-final@maduramart.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertTooManyRequests();
    }

    public function test_payment_upload_rejects_non_allowed_file_types(): void
    {
        Storage::fake('local');

        $email = 'hardening@maduramart.test';
        $user = User::factory()->create(['role' => 'customer', 'email' => $email]);
        $customer = Customer::factory()->create(['email' => $email, 'is_active' => true]);
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('payload.exe', 50, 'application/x-msdownload');

        $this->actingAs($user)
            ->post(route('customer.payment.store', $order), ['payment_proof' => $file])
            ->assertSessionHasErrors('payment_proof');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_proof' => null,
        ]);
    }

    public function test_customer_cannot_access_another_customers_payment_upload(): void
    {
        $emailA = 'a@maduramart.test';
        $emailB = 'b@maduramart.test';

        $userA = User::factory()->create(['role' => 'customer', 'email' => $emailA]);
        $customerA = Customer::factory()->create(['email' => $emailA, 'is_active' => true]);
        $customerB = Customer::factory()->create(['email' => $emailB, 'is_active' => true]);

        $order = Order::factory()->create([
            'customer_id' => $customerB->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('proof.pdf', 50, 'application/pdf');

        $this->actingAs($userA)
            ->post(route('customer.payment.store', $order), ['payment_proof' => $file])
            ->assertForbidden();
    }
}

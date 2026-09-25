<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function customerUser(): array
    {
        $email = fake()->unique()->safeEmail();

        $user = User::factory()->create([
            'role' => 'customer',
            'email' => $email,
        ]);

        $customer = Customer::factory()->create([
            'email' => $email,
            'is_active' => true,
        ]);

        return [$user, $customer];
    }

    public function test_admin_can_review_pending_payment_proofs(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        [, $customer] = $this->customerUser();

        $path = UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf')->store('payment-proofs', 'local');

        Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => $path,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payment-verification.index'))
            ->assertOk()
            ->assertViewIs('admin.payment-verification.index')
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 1);
    }

    public function test_customer_cannot_access_payment_verification(): void
    {
        [$customerUser] = $this->customerUser();

        $this->actingAs($customerUser)
            ->get(route('admin.payment-verification.index'))
            ->assertForbidden();
    }

    public function test_admin_can_download_payment_proof(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'super-admin']);
        [, $customer] = $this->customerUser();

        $path = UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf')->store('payment-proofs', 'local');

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => $path,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payment-verification.proof', $order))
            ->assertOk();
    }

    public function test_admin_can_confirm_or_reject_payment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => 'payment-proofs/proof.pdf',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'paid',
            ])
            ->assertRedirect();

        $this->assertSame('paid', $order->fresh()->payment_status);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
            ])
            ->assertRedirect();

        $this->assertSame('rejected', $order->fresh()->payment_status);
    }
}

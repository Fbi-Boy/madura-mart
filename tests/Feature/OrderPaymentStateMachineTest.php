<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OrderPaymentStateMachineTest extends TestCase
{
    use RefreshDatabase;

    private function customerUser(): array
    {
        $email = fake()->unique()->safeEmail();
        $user = User::factory()->create(['role' => 'customer', 'email' => $email]);
        $customer = Customer::factory()->create(['email' => $email, 'is_active' => true]);
        return [$user, $customer];
    }

    public function test_order_transition_creates_timeline_and_rejects_invalid_transition(): void
    {
        [$user, $customer] = $this->customerUser();
        $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);

        OrderStateMachine::recordInitial($order, $user, 'Pesanan dibuat.');
        OrderStateMachine::transition($order, 'processing', $user, 'Mulai diproses.');

        $this->assertSame('processing', $order->fresh()->status);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'processing',
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        OrderStateMachine::transition($order->fresh(), 'delivered', $user);
    }

    public function test_rejected_payment_requires_reason_and_customer_can_resubmit(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        [$customerUser, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'payment_proof' => 'payment-proofs/old.pdf',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
            ])
            ->assertSessionHasErrors('rejection_reason');

        $this->assertSame('pending', $order->fresh()->payment_status);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
                'rejection_reason' => 'Nominal pada bukti tidak sesuai.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'rejected',
            'payment_rejection_reason' => 'Nominal pada bukti tidak sesuai.',
        ]);

        $file = UploadedFile::fake()->create('proof-baru.pdf', 100, 'application/pdf');

        $this->actingAs($customerUser)
            ->post(route('customer.payment.store', $order), ['payment_proof' => $file])
            ->assertRedirect(route('customer.orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'pending',
            'payment_rejection_reason' => null,
        ]);
    }

    public function test_paid_payment_cannot_be_submitted_again_or_reviewed_again(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);
        [$customerUser, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'payment_proof' => 'payment-proofs/paid.pdf',
        ]);

        $file = UploadedFile::fake()->create('new-proof.pdf', 100, 'application/pdf');

        $this->actingAs($customerUser)
            ->post(route('customer.payment.store', $order), ['payment_proof' => $file])
            ->assertUnprocessable();

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
                'rejection_reason' => 'Tidak valid.',
            ])
            ->assertUnprocessable();
    }

    public function test_order_timeline_is_visible_to_customer(): void
    {
        [$user, $customer] = $this->customerUser();
        $order = Order::factory()->create(['customer_id' => $customer->id, 'status' => 'pending']);
        OrderStateMachine::recordInitial($order, $user, 'Pesanan dibuat.');

        $this->actingAs($user)
            ->get(route('customer.orders.show', $order))
            ->assertOk()
            ->assertSee('Pesanan dibuat.');
    }
}

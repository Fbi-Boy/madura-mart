<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
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

    public function test_operational_roles_cannot_access_payment_verification(): void
    {
        foreach (['gudang', 'kasir', 'purchasing', 'kurir'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.payment-verification.index'))
                ->assertForbidden();
        }
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

        $rejectedOrder = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => 'payment-proofs/rejected-proof.pdf',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $rejectedOrder), [
                'payment_status' => 'rejected',
                'rejection_reason' => 'Bukti pembayaran tidak sesuai.',
            ])
            ->assertRedirect();

        $this->assertSame('rejected', $rejectedOrder->fresh()->payment_status);
    }
    public function test_payment_review_is_written_to_activity_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => 'payment-proofs/proof.pdf',
            'payment_method' => 'qris',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
                'rejection_reason' => 'Bukti pembayaran tidak sesuai.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'payment.rejected',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => "Bukti pembayaran order {$order->order_number} ditolak.",
        ]);

        $activity = ActivityLog::query()
            ->where('action', 'payment.rejected')
            ->where('subject_id', $order->id)
            ->firstOrFail();

        $this->assertSame('rejected', $activity->metadata['payment_status']);
        $this->assertSame('qris', $activity->metadata['payment_method']);
        $this->assertNotEmpty($activity->ip_address);
    }

    public function test_rejection_requires_a_reason(): void
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
                'payment_status' => 'rejected',
            ])
            ->assertSessionHasErrors('rejection_reason');

        $this->assertSame('pending', $order->fresh()->payment_status);
        $this->assertDatabaseMissing('activity_logs', [
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'action' => 'payment.rejected',
        ]);
    }

    public function test_confirmed_payment_records_verification_activity(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'pending',
            'payment_proof' => 'payment-proofs/proof.pdf',
            'payment_method' => 'bank_transfer',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'paid',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'payment.verified',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => "Pembayaran order {$order->order_number} dikonfirmasi.",
        ]);
    }

    public function test_processed_payment_cannot_be_reviewed_again(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'paid',
            'payment_proof' => 'payment-proofs/proof.pdf',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payment-verification.update', $order), [
                'payment_status' => 'rejected',
            ])
            ->assertStatus(422);

        $this->assertSame('paid', $order->fresh()->payment_status);
    }


    public function test_customer_can_resubmit_rejected_payment_and_clears_rejection_reason(): void
    {
        Storage::fake('local');

        [$customerUser, $customer] = $this->customerUser();

        $oldProof = UploadedFile::fake()
            ->create('old-proof.pdf', 100, 'application/pdf')
            ->store('payment-proofs', 'local');

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'rejected',
            'payment_proof' => $oldProof,
            'payment_rejection_reason' => 'Bukti sebelumnya tidak sesuai.',
        ]);

        $newProof = UploadedFile::fake()->create('new-proof.pdf', 100, 'application/pdf');

        $this->actingAs($customerUser)
            ->post(route('customer.payment.store', $order), [
                'payment_proof' => $newProof,
            ])
            ->assertRedirect(route('customer.orders.show', $order));

        $order = $order->fresh();

        $this->assertSame('pending', $order->payment_status);
        $this->assertNull($order->payment_rejection_reason);
        $this->assertNotSame($oldProof, $order->payment_proof);
        Storage::disk('local')->assertMissing($oldProof);
        Storage::disk('local')->assertExists($order->payment_proof);
    }

    public function test_customer_cannot_resubmit_payment_after_it_is_paid(): void
    {
        Storage::fake('local');

        [$customerUser, $customer] = $this->customerUser();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status' => 'paid',
            'payment_proof' => 'payment-proofs/paid-proof.pdf',
        ]);

        $newProof = UploadedFile::fake()->create('new-proof.pdf', 100, 'application/pdf');

        $this->actingAs($customerUser)
            ->post(route('customer.payment.store', $order), [
                'payment_proof' => $newProof,
            ])
            ->assertStatus(422);

        $this->assertSame('paid', $order->fresh()->payment_status);
        Storage::disk('local')->assertMissing($newProof->hashName());
    }

}

<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_can_submit_only_a_draft_purchase_order(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'submitted_at' => null,
        ]);

        $this->actingAs($user)
            ->patch(route('purchasing.purchases.submit', $purchase))
            ->assertRedirect(route('purchasing.purchases.index'))
            ->assertSessionHas('success');

        $this->assertNotNull($purchase->fresh()->submitted_at);
    }

    public function test_submitted_purchase_cannot_be_submitted_or_cancelled_again(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->patch(route('purchasing.purchases.submit', $purchase))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->actingAs($user)
            ->patch(route('purchasing.purchases.cancel', $purchase))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNotNull($purchase->fresh()->submitted_at);
        $this->assertSame('draft', $purchase->fresh()->status);
    }

    public function test_warehouse_can_receive_only_a_submitted_purchase(): void
    {
        $warehouse = User::factory()->create(['role' => 'gudang']);

        $draft = Purchase::factory()->create([
            'status' => 'draft',
            'submitted_at' => null,
        ]);

        $submitted = Purchase::factory()->create([
            'status' => 'draft',
            'submitted_at' => now(),
        ]);

        $this->actingAs($warehouse)
            ->get(route('gudang.penerimaan.index'))
            ->assertOk()
            ->assertSee($submitted->invoice)
            ->assertDontSee($draft->invoice);
    }

    public function test_non_purchasing_users_cannot_submit_purchase_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $purchase = Purchase::factory()->create(['status' => 'draft']);

        $this->actingAs($admin)
            ->patch(route('purchasing.purchases.submit', $purchase))
            ->assertForbidden();
    }
}

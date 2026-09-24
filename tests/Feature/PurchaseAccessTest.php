<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_purchase_transactions(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user)->get(route('admin.purchases.index'))->assertOk();
    }

    public function test_customer_cannot_access_purchase_transactions(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user)->get(route('admin.purchases.index'))->assertForbidden();
    }
}
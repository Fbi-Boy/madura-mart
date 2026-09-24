<?php

namespace Tests\\Feature;

use App\\Models\\Supplier;
use App\\Models\\User;
use Illuminate\\Foundation\\Testing\\RefreshDatabase;
use Tests\\TestCase;

class SupplierAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_suppliers(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get('/admin/suppliers')->assertForbidden();
    }

    public function test_admin_can_view_suppliers(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Supplier::factory()->create(['code' => 'SUP-001', 'name' => 'Supplier Demo']);

        $this->actingAs($user)
            ->get('/admin/suppliers')
            ->assertOk()
            ->assertSee('Supplier Demo');
    }
}

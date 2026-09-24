<?php

namespace Tests\\Feature;

use App\\Models\\Supplier;
use App\\Models\\User;
use Illuminate\\Foundation\\Testing\\RefreshDatabase;
use Tests\\TestCase;

class SupplierValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_core_fields_are_required(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/suppliers', [])
            ->assertSessionHasErrors(['code', 'name']);
    }

    public function test_supplier_code_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Supplier::factory()->create(['code' => 'SUP-001']);

        $this->actingAs($user)
            ->post('/admin/suppliers', [
                'code' => 'SUP-001',
                'name' => 'Supplier Duplikat',
            ])
            ->assertSessionHasErrors(['code']);
    }
}

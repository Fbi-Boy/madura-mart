<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_supplier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post('/admin/suppliers', [
            'code' => 'SUP-100',
            'name' => 'Supplier Baru',
            'contact_person' => 'Andi',
            'phone' => '081234567890',
            'email' => 'andi@example.com',
            'address' => 'Jl. Baru No. 1',
            'city' => 'Pamekasan',
            'is_active' => true,
        ])->assertRedirect('/admin/suppliers');

        $this->assertDatabaseHas('suppliers', [
            'code' => 'SUP-100',
            'name' => 'Supplier Baru',
            'city' => 'Pamekasan',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_supplier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create(['code' => 'SUP-200', 'name' => 'Lama']);

        $this->actingAs($user)->put("/admin/suppliers/{$supplier->id}", [
            'code' => 'SUP-200',
            'name' => 'Baru',
            'contact_person' => 'Budi',
            'phone' => '081111111111',
            'email' => 'budi@example.com',
            'address' => 'Alamat Baru',
            'city' => 'Sampang',
            'is_active' => false,
        ])->assertRedirect('/admin/suppliers');

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Baru',
            'is_active' => 0,
        ]);
    }

    public function test_admin_can_delete_supplier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();

        $this->actingAs($user)
            ->delete("/admin/suppliers/{$supplier->id}")
            ->assertRedirect('/admin/suppliers');

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}

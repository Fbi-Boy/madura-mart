<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchasingSupplierDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_can_view_active_supplier_directory(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        Supplier::factory()->create([
            'code' => 'SUP-A',
            'name' => 'Supplier Aktif',
            'is_active' => true,
        ]);

        Supplier::factory()->create([
            'code' => 'SUP-I',
            'name' => 'Supplier Nonaktif',
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index'))
            ->assertOk()
            ->assertViewIs('purchasing.suppliers.index')
            ->assertViewHas('suppliers', fn ($suppliers) => $suppliers->total() === 1)
            ->assertSee('Supplier Aktif')
            ->assertDontSee('Supplier Nonaktif');
    }

    public function test_purchasing_supplier_directory_supports_search(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        Supplier::factory()->create([
            'code' => 'SUP-JEMBER',
            'name' => 'Supplier Jember',
            'contact_person' => 'Andi',
            'is_active' => true,
        ]);

        Supplier::factory()->create([
            'code' => 'SUP-BESUK',
            'name' => 'Supplier Besuki',
            'contact_person' => 'Budi',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index', ['search' => 'JEMBER']))
            ->assertOk()
            ->assertViewHas('suppliers', fn ($suppliers) => $suppliers->total() === 1)
            ->assertSee('Supplier Jember')
            ->assertDontSee('Supplier Besuki');
    }

    public function test_customer_cannot_access_purchasing_supplier_directory(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('purchasing.suppliers.index'))
            ->assertForbidden();
    }
}

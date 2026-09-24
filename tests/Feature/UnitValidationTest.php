<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unit_core_fields_are_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.units.store'), [])
            ->assertSessionHasErrors(['code', 'name']);
    }

    public function test_unit_code_and_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Unit::factory()->create(['code' => 'UNT-200', 'name' => 'botol']);

        $this->actingAs($admin)->post(route('admin.units.store'), [
            'code' => 'UNT-200',
            'name' => 'botol',
        ])->assertSessionHasErrors(['code', 'name']);
    }
}
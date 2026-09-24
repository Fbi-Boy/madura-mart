<?php

namespace Tests\Feature;

use App\Models\Distributor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributorValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_distributor_core_fields_are_required(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.distributors.store'), [])
            ->assertSessionHasErrors(['code','name']);
    }

    public function test_distributor_code_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Distributor::factory()->create(['code'=>'DST-200']);

        $this->actingAs($user)->post(route('admin.distributors.store'), ['code'=>'DST-200','name'=>'Duplikat'])
            ->assertSessionHasErrors('code');
    }
}
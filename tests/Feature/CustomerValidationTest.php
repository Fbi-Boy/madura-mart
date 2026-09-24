<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_core_fields_are_required(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('admin.customers.store'), [])
            ->assertSessionHasErrors(['code', 'name']);
    }

    public function test_customer_code_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        \App\Models\Customer::factory()->create(['code' => 'CUS-200']);

        $this->actingAs($user)
            ->post(route('admin.customers.store'), [
                'code' => 'CUS-200',
                'name' => 'Duplikat',
            ])
            ->assertSessionHasErrors('code');
    }
}
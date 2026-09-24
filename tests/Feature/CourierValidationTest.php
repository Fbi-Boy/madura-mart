<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_courier_core_fields_are_required(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.couriers.store'), [])
            ->assertSessionHasErrors(['code','name']);
    }

    public function test_courier_code_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Courier::factory()->create(['code'=>'KUR-200']);

        $this->actingAs($user)->post(route('admin.couriers.store'), ['code'=>'KUR-200','name'=>'Duplikat'])
            ->assertSessionHasErrors('code');
    }
}
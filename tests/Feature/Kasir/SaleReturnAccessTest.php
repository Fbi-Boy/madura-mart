<?php

namespace Tests\Feature\Kasir;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleReturnAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_kasir_can_access_returns(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($kasir)->get(route('kasir.retur'))->assertOk();
        $this->actingAs($admin)->get(route('kasir.retur'))->assertForbidden();
    }
}
<?php

namespace Tests\Feature;

use App\Models\Distributor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributorCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_distributor(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.distributors.store'), [
            'code'=>'DST-100','name'=>'Distributor Baru','contact_person'=>'Budi','phone'=>'081200000000','email'=>'baru@example.com','city'=>'Pamekasan','is_active'=>1,
        ])->assertRedirect(route('admin.distributors.index'));

        $this->assertDatabaseHas('distributors', ['code'=>'DST-100','name'=>'Distributor Baru']);
    }

    public function test_admin_can_update_distributor(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $distributor = Distributor::factory()->create(['code'=>'DST-101','name'=>'Lama']);

        $this->actingAs($user)->put(route('admin.distributors.update',$distributor), [
            'code'=>'DST-101','name'=>'Baru','is_active'=>1,
        ])->assertRedirect(route('admin.distributors.index'));

        $this->assertDatabaseHas('distributors', ['id'=>$distributor->id,'name'=>'Baru']);
    }

    public function test_admin_can_delete_distributor(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $distributor = Distributor::factory()->create();

        $this->actingAs($user)->delete(route('admin.distributors.destroy',$distributor))->assertRedirect(route('admin.distributors.index'));

        $this->assertDatabaseMissing('distributors', ['id'=>$distributor->id]);
    }
}
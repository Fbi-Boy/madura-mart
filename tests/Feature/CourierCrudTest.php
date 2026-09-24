<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_courier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.couriers.store'), [
            'code'=>'KUR-100','name'=>'Kurir Baru','phone'=>'081200000000','vehicle_type'=>'Motor','vehicle_number'=>'M 1000 ZZ','is_active'=>1,
        ])->assertRedirect(route('admin.couriers.index'));

        $this->assertDatabaseHas('couriers', ['code'=>'KUR-100','name'=>'Kurir Baru']);
    }

    public function test_admin_can_update_courier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $courier = Courier::factory()->create(['code'=>'KUR-101','name'=>'Lama']);

        $this->actingAs($user)->put(route('admin.couriers.update',$courier), [
            'code'=>'KUR-101','name'=>'Baru','is_active'=>1,
        ])->assertRedirect(route('admin.couriers.index'));

        $this->assertDatabaseHas('couriers', ['id'=>$courier->id,'name'=>'Baru']);
    }

    public function test_admin_can_delete_courier(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $courier = Courier::factory()->create();

        $this->actingAs($user)->delete(route('admin.couriers.destroy',$courier))->assertRedirect(route('admin.couriers.index'));

        $this->assertDatabaseMissing('couriers', ['id'=>$courier->id]);
    }
}
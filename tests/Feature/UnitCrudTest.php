<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_unit(): void { $admin=User::factory()->create(['role'=>'admin']); $this->actingAs($admin)->post(route('admin.units.store'),['code'=>'UNT-100','name'=>'karung','is_active'=>1])->assertRedirect(route('admin.units.index')); $this->assertDatabaseHas('units',['code'=>'UNT-100','name'=>'karung']); }
    public function test_admin_can_update_unit(): void { $admin=User::factory()->create(['role'=>'admin']); $unit=Unit::factory()->create(['code'=>'UNT-101','name'=>'lama']); $this->actingAs($admin)->put(route('admin.units.update',$unit),['code'=>'UNT-101','name'=>'sak','is_active'=>1])->assertRedirect(route('admin.units.index')); $this->assertDatabaseHas('units',['id'=>$unit->id,'name'=>'sak']); }
    public function test_admin_can_delete_unit(): void { $admin=User::factory()->create(['role'=>'admin']); $unit=Unit::factory()->create(); $this->actingAs($admin)->delete(route('admin.units.destroy',$unit))->assertRedirect(route('admin.units.index')); $this->assertDatabaseMissing('units',['id'=>$unit->id]); }
}
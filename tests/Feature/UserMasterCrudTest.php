<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMasterCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user(): void { $admin=User::factory()->create(['role'=>'admin']); $this->actingAs($admin)->post(route('admin.users.store'),['name'=>'Staff Baru','email'=>'staff@example.com','password'=>'password123','password_confirmation'=>'password123','role'=>'kasir'])->assertRedirect(route('admin.users.index')); $this->assertDatabaseHas('users',['email'=>'staff@example.com','role'=>'kasir']); }
    public function test_admin_can_update_user_without_changing_password(): void { $admin=User::factory()->create(['role'=>'admin']); $user=User::factory()->create(['role'=>'kasir']); $this->actingAs($admin)->put(route('admin.users.update',$user),['name'=>'Staff Updated','email'=>$user->email,'role'=>'kurir'])->assertRedirect(route('admin.users.index')); $this->assertDatabaseHas('users',['id'=>$user->id,'name'=>'Staff Updated','role'=>'kurir']); }
    public function test_user_cannot_delete_own_account(): void { $admin=User::factory()->create(['role'=>'admin']); $this->actingAs($admin)->delete(route('admin.users.destroy',$admin))->assertStatus(422); }
}
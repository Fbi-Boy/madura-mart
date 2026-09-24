<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_categories(): void
    {
        $this->get('/admin/categories')->assertRedirect('/login');
    }

    public function test_customer_is_forbidden_from_categories(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin/categories')
            ->assertForbidden();
    }

    public function test_admin_can_view_categories(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['name' => 'Sembako']);

        $this->actingAs($user)
            ->get('/admin/categories')
            ->assertOk()
            ->assertSee('Sembako');
    }
}

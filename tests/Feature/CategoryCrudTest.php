<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_category(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/categories', [
                'name' => 'Produk Segar',
                'description' => 'Buah dan sayuran.',
                'is_active' => '1',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'Produk Segar',
            'slug' => 'produk-segar',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Lama']);

        $this->actingAs($user)
            ->put("/admin/categories/{$category->id}", [
                'name' => 'Baru',
                'description' => 'Diperbarui.',
                'is_active' => '1',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Baru',
            'slug' => 'lama',
        ]);
    }

    public function test_admin_can_delete_category(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}

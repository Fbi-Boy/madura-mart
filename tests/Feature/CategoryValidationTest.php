<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_name_is_required(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post('/admin/categories', ['description' => 'Tanpa nama'])
            ->assertSessionHasErrors('name');
    }

    public function test_category_name_must_be_unique(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['name' => 'Sembako']);

        $this->actingAs($user)
            ->post('/admin/categories', ['name' => 'Sembako'])
            ->assertSessionHasErrors('name');
    }
}

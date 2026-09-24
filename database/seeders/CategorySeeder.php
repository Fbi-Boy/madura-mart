<?php

namespace Database\\Seeders;

use App\\Models\\Category;
use Illuminate\\Database\\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sembako', 'description' => 'Kebutuhan pokok sehari-hari.'],
            ['name' => 'Minuman', 'description' => 'Aneka minuman kemasan dan siap konsumsi.'],
            ['name' => 'Makanan Ringan', 'description' => 'Camilan dan makanan ringan.'],
            ['name' => 'Perawatan Rumah', 'description' => 'Produk kebutuhan kebersihan dan perawatan rumah.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active' => true,
                ],
            );
        }
    }
}

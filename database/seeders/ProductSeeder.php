<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id', 'name');

        $products = [
            ['category' => 'Sembako', 'sku' => 'MM-SMB-001', 'name' => 'Beras Premium 5kg', 'price' => 78000, 'stock' => 25, 'unit' => 'pack'],
            ['category' => 'Sembako', 'sku' => 'MM-SMB-002', 'name' => 'Gula Pasir 1kg', 'price' => 18000, 'stock' => 40, 'unit' => 'pack'],
            ['category' => 'Minuman', 'sku' => 'MM-MNM-001', 'name' => 'Teh Botol 450ml', 'price' => 5000, 'stock' => 60, 'unit' => 'bottle'],
            ['category' => 'Makanan Ringan', 'sku' => 'MM-MKN-001', 'name' => 'Keripik Singkong 100g', 'price' => 9000, 'stock' => 35, 'unit' => 'pack'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'category_id' => $categoryIds[$product['category']],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'unit' => $product['unit'],
                    'is_active' => true,
                ],
            );
        }
    }
}

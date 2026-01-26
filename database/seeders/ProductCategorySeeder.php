<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::insert([
            [
                'image' => null,
                'name' => 'Food',
                'description' => 'This dish is flavorful, freshly prepared, and made with high-quality ingredients.'
            ],
            [
                'image' => null,
                'name' => 'Drink',
                'description' => 'A cold and refreshing beverage, perfect for hot weather'
            ],
        ]);
    }
}

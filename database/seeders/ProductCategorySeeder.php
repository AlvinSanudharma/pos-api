<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductCategory::truncate();
        Schema::enableForeignKeyConstraints();

        ProductCategory::insert([
            [
                'image' => null,
                'name' => 'Food',
                'description' => 'This dish is flavorful, freshly prepared, and made with high-quality ingredients.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Drink',
                'description' => 'A cold and refreshing beverage, perfect for hot weather.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Snack',
                'description' => 'Light and crispy snacks to satisfy your cravings anytime.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Dessert',
                'description' => 'Sweet and delightful treats to end your meal on a perfect note.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Coffee',
                'description' => 'Rich and aromatic coffee brewed from premium selected beans.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Tea',
                'description' => 'Soothing and fragrant tea served hot or iced for any occasion.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Juice',
                'description' => 'Fresh and natural fruit juices packed with vitamins and flavor.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Pastry',
                'description' => 'Freshly baked pastries with buttery layers and delightful fillings.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Ice Cream',
                'description' => 'Creamy and smooth ice cream available in a variety of flavors.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'name' => 'Topping',
                'description' => 'Extra toppings and add-ons to customize your favorite items.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

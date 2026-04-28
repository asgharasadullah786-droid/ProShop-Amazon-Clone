<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Mobiles', 'slug' => 'mobiles'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
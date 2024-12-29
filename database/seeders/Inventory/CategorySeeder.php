<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'ar_name' => 'المنتجات السورية',
            'en_name' => 'Syrian Products',
            // 'image' => 'uploads/category/8af35352e1d00275287e335249ae5162.webp',
        ]);
        Category::create([
            'ar_name' => 'المكسرات',
            'en_name' => 'Nuts',
            // 'image' => 'uploads/category/e4d223526352acd72f8ba063e435d7fc.png',
        ]);
        Category::create([
            'ar_name' => 'زيوت',
            'en_name' => 'Oil',
            // 'image' => 'uploads/category/c28018b3f91d16212dcb2a7719dd47ee.png',
        ]);
        Category::create([
            'ar_name' => 'الألبان والأجبان',
            'en_name' => 'Dairy & cheese',
            // 'image' => 'uploads/category/4facaacfa0189ba1310a682e9a5ab261.png',
        ]);
        Category::create([
            'ar_name' => 'الحبوب والبقوليات',
            'en_name' => 'Cereals & Legumes',
            // 'image' => 'uploads/category/5954245a5c29c0aecf2579153befeaf0.png',
        ]);
        Category::create([
            'ar_name' => 'التوابل',
            'en_name' => 'Spices',
            // 'image' => 'uploads/category/3bb27aec2a7e9de473e676b8dbd36f47.png',
        ]);
        Category::create([
            'ar_name' => 'أغذية مجمدة',
            'en_name' => 'Frozen Food',
            // 'image' => 'uploads/category/fb650cada530cd685dad18b9e4195ae6.png',
        ]);
        Category::create([
            'ar_name' => 'أغذية معلبة وصلصات',
            'en_name' => 'Canned Food & Sauce',
            // 'image' => 'uploads/category/865e86c3e7cd64ba0a914d637666f529.png',
        ]);
        Category::create([
            'ar_name' => 'المشروبات',
            'en_name' => 'Beverages',
            // 'image' => 'uploads/category/11ee2d445d6eb37d8d19f11d7b78f52d.png',
        ]);
        Category::create([
            'ar_name' => 'المنظفات',
            'en_name' => 'Detergents',
            // 'image' => 'uploads/category/ef500eadec795a5123cecb3c36929c63.png',
        ]);
        Category::create([
            'ar_name' => 'بقالة',
            'en_name' => 'Grocery',
            // 'image' => 'uploads/category/00b4a58ba833249096ffc42c47ea02d5.png',
        ]);
        Category::create([
            'ar_name' => 'منتجات التعبئة والتغليف',
            'en_name' => 'Packaging Products',
            // 'image' => 'uploads/category/c3547ef9935678aa592910d9a874bc40.png',
        ]);
    }
}

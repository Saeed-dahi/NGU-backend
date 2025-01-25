<?php

namespace Database\Seeders\Inventory;

use App\Enum\Inventory\ProductType;
use App\Models\Inventory\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'ar_name' => 'منتج رقم ١',
            'en_name' => 'first product',
            'code' => '1',
            'description' => 'Init product',
            'barcode' => '',
            'type' => ProductType::COMMERCIAL,
            'category_id' => '1',
        ]);
    }
}

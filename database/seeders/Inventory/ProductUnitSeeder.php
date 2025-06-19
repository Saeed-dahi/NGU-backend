<?php

namespace Database\Seeders\Inventory;

use App\Enum\Inventory\ProductType;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductUnit::create([
            'product_id' => 1,
            'unit_id' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Store;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'ar_name' => 'المنتجات السورية',
            'en_name' => 'Syrian Products',
            'description' => '',
        ]);
        Store::create([
            'ar_name' => 'زيوت',
            'en_name' => 'Oils',
            'description' => '',
        ]);
        Store::create([
            'ar_name' => 'بقوليات',
            'en_name' => 'Legumes',
            'description' => '',
        ]);
    }
}

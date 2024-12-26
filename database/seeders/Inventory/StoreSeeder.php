<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'ar_name' => 'المستودع الرئيسي',
            'en_name' => 'Main Store',
            'description' => '',
        ]);
        Store::create([
            'ar_name' => 'المستودع الثاني',
            'en_name' => 'Second Store',
            'description' => '',
        ]);
        Store::create([
            'ar_name' => 'المستودع الثالث',
            'en_name' => 'Third Store',
            'description' => '',
        ]);
    }
}

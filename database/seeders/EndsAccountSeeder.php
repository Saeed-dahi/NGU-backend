<?php

namespace Database\Seeders;

use App\Models\EndsAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EndsAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EndsAccount::create([
            'en_name' => 'budget',
            'ar_name' => 'الميزانية',
        ]);
        EndsAccount::create([
            'en_name' => 'Profit and loss',
            'ar_name' => 'الارباح والخسائر',
        ]);
        EndsAccount::create([
            'en_name' => 'Trading',
            'ar_name' => 'المتاجرة',
        ]);
    }
}

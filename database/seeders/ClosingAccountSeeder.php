<?php

namespace Database\Seeders;

use App\Models\ClosingAccount;

use Illuminate\Database\Seeder;

class ClosingAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClosingAccount::create([
            'en_name' => 'budget',
            'ar_name' => 'الميزانية',
        ]);
        ClosingAccount::create([
            'en_name' => 'Profit and loss',
            'ar_name' => 'الارباح والخسائر',
        ]);
        ClosingAccount::create([
            'en_name' => 'Trading',
            'ar_name' => 'المتاجرة',
        ]);
    }
}

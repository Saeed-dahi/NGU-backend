<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assets
        Account::create([
            'id' => 1,
            'code' => 1,
            'ar_name' => 'الموجودات',
            'en_name' => 'Assets',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => null,
            'balance' => 0,
            'closing_account_id' => 1
        ]);

        Account::create([
            'id' => 2,
            'code' => 11,
            'ar_name' => 'الموجودات الثابتة',
            'en_name' => 'Fixed Assets',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 1,
            'balance' => 0,
            'closing_account_id' => 1
        ]);

        Account::create([
            'id' => 3,
            'code' => 111,
            'ar_name' => 'اراضي',
            'en_name' => 'Lands',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 2,
            'balance' => 0,
            'closing_account_id' => 1
        ]);


        // Liabilities
        Account::create([
            'id' => 4,
            'code' => 2,
            'ar_name' => 'الخصوم',
            'en_name' => 'Liabilites',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => null,
            'balance' => 0,
            'closing_account_id' => 1
        ]);

        Account::create([
            'id' => 5,
            'code' => 21,
            'ar_name' => 'رأس المال',
            'en_name' => 'Capital',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 4,
            'balance' => 0,
            'closing_account_id' => 1
        ]);

        Account::create([
            'id' => 6,
            'code' => 211,
            'ar_name' => 'رأس المال المدفوع',
            'en_name' => 'Paid-up Capital',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 5,
            'balance' => 0,
            'closing_account_id' => 1
        ]);


        // Expenses
        Account::create([
            'id' => 7,
            'code' => 3,
            'ar_name' => 'الاستخدامات',
            'en_name' => 'Expenses',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => null,
            'balance' => 0,
            'closing_account_id' => 1
        ]);
        Account::create([
            'id' => 8,
            'code' => 31,
            'ar_name' => 'الاجور',
            'en_name' => 'Payroll',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 7,
            'balance' => 0,
            'closing_account_id' => 1
        ]);
        Account::create([
            'id' => 9,
            'code' => 311,
            'ar_name' => 'أجور نقدية',
            'en_name' => 'Cash Wages',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 8,
            'balance' => 0,
            'closing_account_id' => 1
        ]);

        // Revenues
        Account::create([
            'id' => 10,
            'code' => 4,
            'ar_name' => 'الموارد',
            'en_name' => 'Revenues',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => null,
            'balance' => 0,
            'closing_account_id' => 1
        ]);
        Account::create([
            'id' => 11,
            'code' => 43,
            'ar_name' => 'إيرادات تشغيل للغير',
            'en_name' => 'Operating Revenues',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 10,
            'balance' => 0,
            'closing_account_id' => 1
        ]);
        Account::create([
            'id' => 12,
            'code' => 4301,
            'ar_name' => 'إيراد 1',
            'en_name' => 'Revenue 1',
            'account_type' => 'main',
            'account_nature' => null,
            'account_category' => null,
            'parent_id' => 11,
            'balance' => 0,
            'closing_account_id' => 1
        ]);
    }
}

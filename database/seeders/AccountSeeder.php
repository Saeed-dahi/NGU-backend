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
        // Account::create([
        //     'id' => 1,
        //     'code' => 1,
        //     'ar_name' => 'الموجودات',
        //     'en_name' => 'Assets',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => null,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);

        // Account::create([
        //     'id' => 2,
        //     'code' => 11,
        //     'ar_name' => 'الموجودات الثابتة',
        //     'en_name' => 'Fixed Assets',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 1,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);

        // Account::create([
        //     'id' => 3,
        //     'code' => 111,
        //     'ar_name' => 'اراضي',
        //     'en_name' => 'Lands',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 2,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);


        // // Liabilities
        // Account::create([
        //     'id' => 4,
        //     'code' => 2,
        //     'ar_name' => 'الخصوم',
        //     'en_name' => 'Liabilites',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => null,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);

        // Account::create([
        //     'id' => 5,
        //     'code' => 21,
        //     'ar_name' => 'رأس المال',
        //     'en_name' => 'Capital',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 4,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);

        // Account::create([
        //     'id' => 6,
        //     'code' => 211,
        //     'ar_name' => 'رأس المال المدفوع',
        //     'en_name' => 'Paid-up Capital',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 5,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);


        // // Expenses
        // Account::create([
        //     'id' => 7,
        //     'code' => 3,
        //     'ar_name' => 'الاستخدامات',
        //     'en_name' => 'Expenses',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => null,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);
        // Account::create([
        //     'id' => 8,
        //     'code' => 31,
        //     'ar_name' => 'الاجور',
        //     'en_name' => 'Payroll',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 7,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);
        // Account::create([
        //     'id' => 9,
        //     'code' => 311,
        //     'ar_name' => 'أجور نقدية',
        //     'en_name' => 'Cash Wages',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 8,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);

        // // Revenues
        // Account::create([
        //     'id' => 10,
        //     'code' => 4,
        //     'ar_name' => 'الموارد',
        //     'en_name' => 'Revenues',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => null,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);
        // Account::create([
        //     'id' => 11,
        //     'code' => 43,
        //     'ar_name' => 'إيرادات تشغيل للغير',
        //     'en_name' => 'Operating Revenues',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 10,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);
        // Account::create([
        //     'id' => 12,
        //     'code' => 4301,
        //     'ar_name' => 'إيراد 1',
        //     'en_name' => 'Revenue 1',
        //     'account_type' => 'main',
        //     'account_nature' => null,
        //     'account_category' => null,
        //     'parent_id' => 11,
        //     'balance' => 0,
        //     'closing_account_id' => 1
        // ]);




        // Define all account data, omitting the 'parent_id' initially
        $accounts = [
            ['id' => 1, 'code' => '1', 'en_name' => 'Assets', 'ar_name' => 'الموجودات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 2, 'code' => '11', 'en_name' => 'Fixed Assets', 'ar_name' => 'الموجودات الثابتة', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 3, 'code' => '111', 'en_name' => 'Lands', 'ar_name' => 'اراضي', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '11'],
            ['id' => 4, 'code' => '2', 'en_name' => 'Liabilities', 'ar_name' => 'الخصوم', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 5, 'code' => '21', 'en_name' => 'Capital', 'ar_name' => 'رأس المال', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '2'],
            ['id' => 6, 'code' => '211', 'en_name' => 'Paid-up Capital', 'ar_name' => 'رأس المال المدفوع', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '21'],
            ['id' => 7, 'code' => '3', 'en_name' => 'Expenses', 'ar_name' => 'الاستخدامات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 8, 'code' => '31', 'en_name' => 'Payroll', 'ar_name' => 'الاجور', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '3'],
            ['id' => 9, 'code' => '311', 'en_name' => 'Cash Wages', 'ar_name' => 'أجور نقدية', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '31'],
            ['id' => 10, 'code' => '4', 'en_name' => 'Revenues', 'ar_name' => 'الموارد', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 11, 'code' => '43', 'en_name' => 'Net Sales', 'ar_name' => 'صافي مبيعات البضائع بغرض البيع', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '4'],
            ['id' => 12, 'code' => '431', 'en_name' => 'Gross Sales', 'ar_name' => 'إجمالي المبيعات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '43'],
            ['id' => 13, 'code' => '15', 'en_name' => 'Debit Accounts', 'ar_name' => 'مدينون', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 14, 'code' => '151', 'en_name' => 'Commercial Debitors', 'ar_name' => 'الزبائن', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '15'],
            ['id' => 15, 'code' => '1511', 'en_name' => 'Client n 1', 'ar_name' => 'زبون رقم ١', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '151'],
            ['id' => 16, 'code' => '18', 'en_name' => 'Cash', 'ar_name' => 'أموال جاهزة', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 17, 'code' => '181', 'en_name' => 'Petty Cash', 'ar_name' => 'الصندوق', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '18'],
            ['id' => 18, 'code' => '182', 'en_name' => 'Banks', 'ar_name' => 'المصارف', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '18'],
            ['id' => 19, 'code' => '1821', 'en_name' => 'Bank 1', 'ar_name' => 'مصرف 1', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '182'],
        ];

        // Create accounts without parent IDs
        $createdAccounts = [];
        foreach ($accounts as $accountData) {
            $account = Account::create([
                'id' => $accountData['id'],
                'code' => $accountData['code'],
                'en_name' => $accountData['en_name'],
                'ar_name' => $accountData['ar_name'],
                'account_type' => $accountData['account_type'],
                'balance' => $accountData['balance'],
                'closing_account_id' => $accountData['closing_account_id'],
            ]);
            $createdAccounts[$accountData['code']] = $account;
        }

        // Update accounts with their parent IDs
        foreach ($accounts as $accountData) {
            if (isset($accountData['parent_code'])) {
                $account = $createdAccounts[$accountData['code']];
                $parent = $createdAccounts[$accountData['parent_code']];
                $account->update(['parent_id' => $parent->id]);
            }
        }
    }
}

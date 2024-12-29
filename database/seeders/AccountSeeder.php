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
        // Define all account data, omitting the 'parent_id' initially
        $accounts = [
            ['id' => 1, 'code' => '1', 'en_name' => 'Assets', 'ar_name' => 'الموجودات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 2, 'code' => '11', 'en_name' => 'Fixed Assets', 'ar_name' => 'الموجودات الثابتة', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 3, 'code' => '111', 'en_name' => 'Lands', 'ar_name' => 'اراضي', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '11'],

            ['id' => 4, 'code' => '2', 'en_name' => 'Liabilities', 'ar_name' => 'الخصوم', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 5, 'code' => '21', 'en_name' => 'Capital', 'ar_name' => 'رأس المال', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '2'],
            ['id' => 6, 'code' => '211', 'en_name' => 'Paid-up Capital', 'ar_name' => 'رأس المال المدفوع', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '21'],

            ['id' => 7, 'code' => '3', 'en_name' => 'Expenses', 'ar_name' => 'الاستخدامات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1],
            ['id' => 8, 'code' => '31', 'en_name' => 'Payroll', 'ar_name' => 'الاجور', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 2, 'parent_code' => '3'],
            ['id' => 9, 'code' => '311', 'en_name' => 'Cash Wages', 'ar_name' => 'أجور نقدية', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 2, 'parent_code' => '31'],

            ['id' => 10, 'code' => '4', 'en_name' => 'Revenues', 'ar_name' => 'الموارد', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 2],
            ['id' => 11, 'code' => '43', 'en_name' => 'Net Sales', 'ar_name' => 'صافي مبيعات البضائع بغرض البيع', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '4'],
            ['id' => 12, 'code' => '431', 'en_name' => 'Gross Sales', 'ar_name' => 'إجمالي المبيعات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '43'],

            ['id' => 13, 'code' => '18', 'en_name' => 'Cash', 'ar_name' => 'أموال جاهزة', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 14, 'code' => '181', 'en_name' => 'Petty Cash', 'ar_name' => 'الصندوق', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '18'],
            ['id' => 15, 'code' => '182', 'en_name' => 'Banks', 'ar_name' => 'المصارف', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '18'],
            ['id' => 16, 'code' => '1821', 'en_name' => 'Bank 1', 'ar_name' => 'مصرف 1', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '182'],

            ['id' => 17, 'code' => '14', 'en_name' => 'Inventory', 'ar_name' => 'المخزون', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 18, 'code' => '143', 'en_name' => 'Completed Products', 'ar_name' => 'إنتاج تام', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '14'],
            ['id' => 19, 'code' => '1430', 'en_name' => 'Completed Products (End of Period)', 'ar_name' => 'بضاعة آخر المدة', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '143'],
            ['id' => 20, 'code' => '1431', 'en_name' => 'Completed Products Opening Stocks', 'ar_name' => 'بضاعة أول المدة', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '143'],

            ['id' => 21, 'code' => '15', 'en_name' => 'Debit Accounts', 'ar_name' => 'مدينون', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '1'],
            ['id' => 22, 'code' => '151', 'en_name' => 'Customers', 'ar_name' => 'الزبائن', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '15'],
            ['id' => 23, 'code' => '1510001', 'en_name' => 'Client 1', 'ar_name' => 'زبون رقم ١', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '151'],

            ['id' => 24, 'code' => '34', 'en_name' => 'Purchases', 'ar_name' => 'مشتريات بغرض البيع', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '3'],
            ['id' => 25, 'code' => '341', 'en_name' => 'Purchases (Purchase value)', 'ar_name' => '(مشتريات بغرض البيع (قيمة المشتريات', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 3, 'parent_code' => '34'],

            ['id' => 26, 'code' => '22', 'en_name' => 'Expenditures', 'ar_name' => 'الالتزامات', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '2'],
            ['id' => 27, 'code' => '223', 'en_name' => 'Credit Accounts', 'ar_name' => 'الحسابات الدائنة', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '22'],
            ['id' => 28, 'code' => '2231', 'en_name' => 'Suppliers', 'ar_name' => 'المورودون', 'account_type' => 'main', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '223'],
            ['id' => 29, 'code' => '2231001', 'en_name' => 'Supplier 1', 'ar_name' => 'مورد 1', 'account_type' => 'sub', 'balance' => '0.00', 'closing_account_id' => 1, 'parent_code' => '2231'],
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

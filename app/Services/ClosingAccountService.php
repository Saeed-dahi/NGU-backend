<?php

namespace App\Services;

use App\Enum\Account\AccountType;
use App\Http\Resources\ClosingAccount\ClosingAccountsStatementResource;
use App\Models\Account;
use App\Models\ClosingAccount;
use Exception;

class ClosingAccountService
{
    public function closingAccountsStatement($request)
    {
        $tradingAccount = ClosingAccount::firstWhere('en_name', 'Trading');
        $profitLossAccount = ClosingAccount::firstWhere('en_name', 'Profit and Loss');
        $budgetAccount = ClosingAccount::firstWhere('en_name', 'Budget');



        $completedProductsAccount = $this->setCompletedProductAccountBalance($request->completed_product_value ?? 0);


        if (!$tradingAccount || !$profitLossAccount || !$budgetAccount) {
            throw new Exception("Not Found", 404);
        }

        $tradingStatement = $this->closingAccountStatement($tradingAccount, 0, 19);

        $profitAndLossStatement = $this->closingAccountStatement($profitLossAccount, $tradingStatement->value);

        $budgetStatement = $this->closingAccountStatement($budgetAccount, $profitAndLossStatement->value);

        $data = [
            'trading' => ClosingAccountsStatementResource::make($tradingStatement),
            'profit_loss' => ClosingAccountsStatementResource::make($profitAndLossStatement),
            'budget' => ClosingAccountsStatementResource::make($budgetStatement),
        ];

        return $data;
    }

    public function closingAccountStatement(ClosingAccount $closingAccount, $previousValue = 0, $customAccountsToAdd = 0)
    {
        $debitAccounts = $closingAccount->accounts()->where('account_type', AccountType::SUB)->where('balance', '>=', 0)->get();
        $creditAccounts = $closingAccount->accounts()->where('account_type', AccountType::SUB)->where('balance', '<', 0)->orWhere('id', $customAccountsToAdd)->get();

        $debitBalance = $debitAccounts->sum(fn($account) => abs($account->balance));
        $creditBalance = $creditAccounts->sum(fn($account) => abs($account->balance));
        $value = ($previousValue + $creditBalance) - $debitBalance;

        return (object)[
            'debit_accounts' => $debitAccounts,
            'credit_accounts' => $creditAccounts,
            'revenue' => $debitBalance,
            'expense' => $creditBalance + $previousValue,
            'value' => $value
        ];
    }
    public function setCompletedProductAccountBalance($completedProductsValue = 45000)
    {
        $completedProductsAccount = Account::firstWhere('id', 19);
        $completedProductsAccount->balance = $completedProductsValue;
        $completedProductsAccount->save();

        return $completedProductsAccount;
    }
}

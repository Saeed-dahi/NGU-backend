<?php

namespace App\Services;

use App\Enum\Account\AccountType;
use App\Http\Resources\ClosingAccount\ClosingAccountsStatementResource;
use App\Models\ClosingAccount;
use Exception;

class ClosingAccountService
{
    public function closingAccountsStatement()
    {
        $tradingAccount = ClosingAccount::firstWhere('en_name', 'Trading');
        $profitLossAccount = ClosingAccount::firstWhere('en_name', 'Profit and Loss');
        $budgetAccount = ClosingAccount::firstWhere('en_name', 'Budget');

        $completedProductsValue = 45000;

        if (!$tradingAccount || !$profitLossAccount || !$budgetAccount) {
            throw new Exception("Not Found", 404);
        }

        $tradingStatement = $this->closingAccountStatement($tradingAccount, $completedProductsValue);

        $profitAndLossStatement = $this->closingAccountStatement($profitLossAccount, $tradingStatement->value);

        $budgetStatement = $this->closingAccountStatement($budgetAccount, $profitAndLossStatement->value - $completedProductsValue);

        $data = [
            'trading' => ClosingAccountsStatementResource::make($tradingStatement),
            'profit_loss' => ClosingAccountsStatementResource::make($profitAndLossStatement),
            'budget' => ClosingAccountsStatementResource::make($budgetStatement),
        ];

        return $data;
    }

    public function closingAccountStatement(ClosingAccount $closingAccount, $previousValue = 0)
    {
        $debitAccounts = $closingAccount->accounts()->where('account_type', AccountType::SUB)->where('balance', '>=', 0)->get();
        $creditAccounts = $closingAccount->accounts()->where('account_type', AccountType::SUB)->where('balance', '<', 0)->get();

        $debitBalance = abs($debitAccounts->sum('balance'));
        $creditBalance = abs($creditAccounts->sum('balance'));
        $value = ($previousValue + $creditBalance) - $debitBalance;

        return (object)[
            'debit_accounts' => $debitAccounts,
            'credit_accounts' => $creditAccounts,
            'revenue' => $debitBalance,
            'expense' => $creditBalance + $previousValue,
            'value' => $value
        ];
    }
}

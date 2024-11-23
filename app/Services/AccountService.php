<?php

namespace App\Services;

use App\Enum\Account\AccountNature;
use App\Enum\Account\AccountType;
use App\Enum\Status;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\SharedFunctions;
use App\Models\Account;
use App\Models\Transaction;


class AccountService
{
    use ApiResponser, SharedFunctions;

    function createNewAccount($request)
    {
        $validatedRequest = $request->validated();

        // check if parent account is Main
        if ($request->has('parent_id')) {
            $parentAccount = Account::findOrFail($request->parent_id);
            if (!$this->isMainAccount($parentAccount)) {
                throw new \Exception(__('lang.error_account_type'), 400);
            }
        }
        // Always budget is the the closing account
        $validatedRequest = array_merge($validatedRequest, ['closing_account_id' => 1]);


        $account = Account::create($validatedRequest);

        return $account;
    }

    function updateAccount($request, $account)
    {
        $validatedRequest = $request->validated();
        // TODO
        // if user change account code, change parent per new code
        // if ($request->has('code')) {
        //     $parent = $this->getParentFromCode($request->code);
        //     $validatedRequest['parent_id'] = $parent->id ?? null;
        // }
        $account->update($validatedRequest);

        return $account;
    }

    function searchAccount($query)
    {
        $query = $this->customSearchNormalize($query);
        $accounts = Account::whereRaw("REPLACE(REPLACE(REPLACE(ar_name, 'أ', 'ا'), 'إ', 'ا'), 'ء', '') LIKE ?", ['%' . $query . '%'])
            ->orWhere('en_name', 'LIKE', '%' . $query . '%')
            ->orWhere('code', 'LIKE', '%' . $query . '%')->get();

        return $accounts;
    }

    function isMainAccount($account)
    {
        return $account->account_type == AccountType::MAIN->value;
    }

    function isSubAccount($account)
    {
        return $account->account_type == AccountType::SUB->value;
    }

    function getParentFromCode($code)
    {
        for ($i = strlen($code) - 1; $i > 0; $i--) {
            $parentCode = substr($code, 0, $i);
            $parentAccount = Account::where('code', $parentCode)->first();
            if ($parentAccount) {
                if ($this->isMainAccount($parentAccount)) {
                    return $parentAccount;
                }
            }
        }
        return null;
    }

    function getSuggestedCodePerParent(Account $parentAccount)
    {
        $lastSub = $parentAccount->subAccounts()->latest('id')->first();
        if ($lastSub) {
            $suggestedCode = $lastSub->code + 1;
        } else {
            $suggestedCode = $parentAccount->code . '1';
        }


        return $suggestedCode;
    }

    /**
     * this function will help me to automatic update account balance depend on it's transactions
     * @param Account,Transactions (we can get it use relationship)
     */
    function updateAccountBalanceAutomatically($account, $transactions)
    {
        $newBalance = 0;
        foreach ($transactions as $key => $transaction) {
            $amount = $transaction->type == AccountNature::DEBIT->value ? $transaction->amount : -$transaction->amount;
            $newBalance += $amount;
        }
        $account->update(['balance' => $newBalance]);
    }

    function updateAccountBalance($transaction)
    {
        $account = $transaction->account;
        $transactable = $transaction->transactable;
        $amount = $transaction->type == AccountNature::DEBIT->value ? $transaction->amount : -$transaction->amount;
        $incrementAmount  = $transactable->status == Status::SAVED->value ?  +$amount :  -$amount;

        if ($transactable->created_at != $transactable->updated_at) {
            $account->increment('balance', $incrementAmount);
        }
    }
}
